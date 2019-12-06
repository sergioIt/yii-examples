<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 17.09.18
 * Time: 13:46
 */

namespace app\commands;

use app\components\App;
use app\components\ConsoleController;
use app\models\CustomerComments;
use app\models\Customers;
use app\services\CustomersService;
use GuzzleHttp\Exception\RequestException;
use Jupiter\BinaryPlatform\PrivateApiSdk\Env;
use Jupiter\BinaryPlatform\PrivateApiSdk\PrivateApi;
use yii\base\Exception;
use yii\console\ExitCode;
use app\traits\ConsoleTrait;
use yii\db\Expression;
use yii\redis\Connection;
use yii\helpers\Json;
use app\exceptions\CustomersServiceException;
use yii\helpers\ArrayHelper;
use app\models\Customer;

/**
 * Class CustomersController
 * @package app\commands
 *
 * @property string connection
 */
class CustomersController extends ConsoleController
{
    const LOG_CATEGORY = 'console-customers';

    const LOG_CUSTOM_CATEGORY = 'console-customers-sync-column';

    /**
     * @var int
     */
    public $size = 1000;

    /**
     * @var int|null
     */
    public $fromUserId;

    /**
     * @var string
     */
    public $connection = 'db2_analytics';

    /**
     * @param string $actionID
     * @return array
     */
    public function options($actionID)
    {
        return ArrayHelper::merge([
            'size',
            'connection',
            'fromUserId',
        ], parent::options($actionID));
    }


    /**
     * синхронит даныне из заданного столбца табицы db2.users в таблицу db.customers
     *
     * пока что сделано только для поля phone_confirmed
     * причём синхронятся только значения 1
     *
     * @param string $column
     * @return int
     *
     *
     * @todo убрать из крона после того как будет реализованиа верификация телефона из crm
     *
     * @throws Exception
     */
    public function actionSyncColumn(string $column)
    {

        $chunkSize = 5000;

        $updatedCustomers = [];

        if ($column !== 'phone_confirmed') {

            throw new Exception('column ' . $column . ' not allowed for sync');
        }

        $query = Customer::find()->select(['id'])
            ->where(['phone_confirmed' => Customer::PHONE_CONFIRMED_STATE]);

        $query->orderBy(['id' => SORT_DESC]);

        $existedCount = $query->count('1');
        $existedMaxPage = ceil($existedCount / $chunkSize);

        for ($i = 1; $i <= $existedMaxPage; $i++) {

            $offset = ($i - 1) * $chunkSize;

            $query->offset($offset);
            $query->limit($chunkSize);
            // порция юзеров из db2, у которых верифицирован телефон
            $users = $query->asArray()->column();

            // порция юзеров из db, у которых те же id, но НЕ верифицрован телефон
            $localCustomersDiff = Customers::find()
                ->select(['id'])
                // почему-то простое условие != 1 не срабатывает, даже на уровне sql-запроса, поэтому приходится вот так
                ->andWhere(['or', new Expression('phone_confirmed = 0'), new Expression('phone_confirmed is null')])
                ->andWhere(['in', 'id', $users])
                ->asArray()->column();


            // если такие нашлись, то апдейтим статус верификци телефон на "верифицирован"
            if (count($localCustomersDiff) > 0) {

                \Yii::$app->db->createCommand()
                    ->update(Customers::tableName(),
                        ['phone_confirmed' => Customer::PHONE_CONFIRMED_STATE],
                        ['in', 'id', $localCustomersDiff]
                    )
                    ->execute();

                $updatedCustomers = ArrayHelper::merge($updatedCustomers, $localCustomersDiff);
            }

            if ($this->screen) {

                ConsoleTrait::showProgress($i, $existedMaxPage);
            }

        }

        if (count($updatedCustomers) > 0) {

            $message = 'customers.phone_confirmed synced to 1 : ' . Json::encode($updatedCustomers);

            \Yii::info($this->id . '/' . $this->action->id . ': ' . $message, self::LOG_CUSTOM_CATEGORY);
        }

        return ExitCode::OK;
    }


    /**
     * @param string $listName
     * @return int
     */
    public function actionSyncOperations($listName = CustomersService::REDIS_KEY_EXPORT_OPERATIONS):int
    {

        /** @var Connection $redis */
        $redis = \Yii::$app->redis;

        if (!$redis->llen($listName)) { // если пустой список
            return ExitCode::OK;
        }

        /**
         * @var  $customersService CustomersService
         */
        $customersService = new CustomersService();

        while ($item = $redis->lpop($listName)) {

            $itemArray = Json::decode($item);

            try {

                $customersService->checkOperationsData($itemArray);

                $mapped = $customersService->mapOperationsData($itemArray);
                // update таблицы customers
                $customerId = $itemArray['user_id'];
                $updated = $customersService->updateOperations($customerId, $mapped);

                if ($updated > 0) {
                    $this->log('Customer #' . $customerId . ' redis data: : ' . Json::encode($customersService->redisData));
                }

            } catch (CustomersServiceException $e) {
                $this->error($e->getMessage());

            } catch (Exception $e) {

                $this->error($e->getMessage());
            }

        }

        return ExitCode::OK;
    }

}
