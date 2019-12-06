<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 04.09.17
 * Time: 13:56
 */

namespace app\commands;

use app\components\ConsoleController;
use app\helpers\param\ExternalCallEngineParam;
use app\models\CallListSearch;
use app\models\Calls;
use app\models\Country;
use app\services\CustomersService;
use yii\base\Exception;
use yii\console\ExitCode;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class CallController
 *
 * @package app\commands
 *
 * manage command dealing with external call engine app
 *
 * @property $from
 */
class CallController extends ConsoleController
{
    const LOG_CATEGORY = 'console-call';

    /**
     * Используется в запросе при поиске
     *  существующих звонков
     */
    const CHUNK_SIZE = 1000;

    /**
     * @var int
     */
    public $limit = 2000;

    /**
     * @var bool
     */
    public $all = false;

    /**
     * Обновлять ли найденые звонки
     * @var bool
     */
    public $update = false;

    /**
     * Дополнительные параметры запроса
     * @var string json-строка
     */
    public $params = '{}';

    /**
     * @param string $actionID
     *
     * @return array
     */
    public function options($actionID): array
    {
        return ArrayHelper::merge([
            'limit',
            'all',
            'params',
            'update',
        ], parent::options($actionID));
    }

    /**
     * Синхронизация всех звонков. Параметры: --limit (default: 1000), --all (ignore > last id condition), --params (json string)
     *
     * Поиск customer_id по номеру телефона
     * Поиск country_id по коду страны
     *
     * Пример команды
     * ./yii call/sync -scr --params="{\"date_from\":\"2019-04-14\", \"status\": \"NO ANSWER\", \"call_type\": \"outbound\"}" --update --all --limit=500
     *
     * @return int
     * @throws \yii\base\InvalidArgumentException
     */
    public function actionSync(): int
    {
        $searchModel     = new CallListSearch();
        $customerService = new CustomersService();

        # check last date
        $lastRowId = ( ! $this->all) ? Calls::find()->select(['row_id'])->orderBy(new Expression('row_id DESC NULLS LAST'))->scalar() : null;

        # request params
        $params = [
            'token'        => ExternalCallEngineParam::getToken(),
            'limit'        => $this->limit,
            'after_row_id' => $lastRowId,
        ];

        # дополнительные пар-ры можем принять и смержить,
        # всякое бывает, как мы могли убедиться
        $additionalParams = (array)json_decode($this->params,true);
        $params = ArrayHelper::merge($params, $additionalParams);

        try { # api может быть недоступно
            $data = $searchModel->getData($params);
        } catch (Exception $e) {
            $this->error('call/sync get data exception: ' . $e->getMessage());

            return ExitCode::DATAERR;
        }

        # счетчики
        $created = $updated = 0;

        # получили какие то звонки
        if ($data['count'] > 0) {

            # бьем все, что нашли, на части, чтобы подставлять в запрос
            $callsChunks = array_chunk($data['result'], self::CHUNK_SIZE, true);

            # ищем для каждой пачки звонки в БД
            foreach ($callsChunks as $chunk) {

                # ищем пачку в БД
                # проверка $lastRowId нужна для того, что раз уж мы ищем по этому полю,
                # а это последний порядковый номер записи, предполагаем, что у нас его в бд нет
                # и надо его добавить
                /** @var Calls[] $foundCalls */
                $foundCalls = $lastRowId ? [] : Calls::find()->andWhere(['in', 'id', array_keys($chunk)])
                              ->indexBy('id')->all();

                # обрабатываем звонки текущего куска
                foreach ($chunk as $call) {

                    # если нашли звонок у себя и обновление нужно - берем за основу найденную модель
                    # если не нашли - создаем модель
                    # иначе (найдено, но обновление не нужно) - пропуск
                    if (array_key_exists($call['id'], $foundCalls) && $this->update) {
                        $model = $foundCalls[$call['id']];
                    } elseif (!array_key_exists($call['id'], $foundCalls)) {
                        $model = new Calls();
                        $model->setAttribute('id', $call['id']);
                    } else {
                        # если апдейтить не надо - идем дальше
                        continue;
                    }

                    $country = $call['country'];
                    # костылек со страной
                    if ($call['country'] === 'VNM_OLD') {
                        $country = 'VNM';
                    }
                    # костылек со страной 2
                    if ($call['country'] === 'IND_OLD') {
                        $country = 'IND';
                    }

                    $countryId  = Country::find()->cache(3600)->where(['code' => $country])->select(['id'])->scalar();
                    $customerId = $customerService->getCustomerByPhoneNumbersQuery(
                        $call['call_type'] === Calls::TYPE_INBOUND ? $call['phone_from'] : $call['phone_to']
                    )->select(['u.id'])->scalar();

                    $updateData = [
                        'date'           => $call['date'],
                        'user_id'        => $call['user_id'],
                        'phone'          => $call['phone_from'],
                        'country'        => $call['country'],
                        'phone_from'     => $call['phone_from'],
                        'phone_to'       => $call['phone_to'],
                        'duration'       => $call['duration'],
                        'status'         => $call['status'],
                        'record'         => $call['record'],
                        'type'           => $call['call_type'],
                        'method'         => $call['method'],
                        'row_id'         => $call['row_id'],
                        'answer_waiting' => (int)$call['answer_waiting'],
                        'ivr'            => $call['ivr_type'] !== '' ? (int)$call['ivr_type'] : null,

                        'country_id'  => ! $countryId ? null : $countryId,
                        'customer_id' => ! $customerId ? null : $customerId,
                    ];

                    $model->setAttributes($updateData);
                    $isNewRecord = $model->isNewRecord;

                    if ( ! $model->save()) {
                        $this->error('Calls save error: ' . Json::encode($model->getErrors()) .
                                     '; call data: ' . Json::encode($updateData));
                    } else {

                        # обновляем счетчики, пишем в консоль, что все проиходит
                        if ($isNewRecord) {
                            $created++;
                        } else {
                            $updated++;
                        }
                        $this->stdout('Call #' . $call['id'] . ' ' . ($isNewRecord ? 'created' : 'updated'));
                    }
                }
            }
        }

        $this->log('Request parameters: ' . json_encode($params));
        $this->log('Found calls: ' . $data['count']);
        $this->log('Created: ' . $created);
        $this->log('Updated: ' . $updated);

        return ExitCode::OK;
    }
}
