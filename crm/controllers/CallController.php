<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 08.12.17
 * Time: 15:58
 */

namespace app\controllers;

use app\helpers\RbacHelper;
use app\models\CallsSearch;
use app\models\crm\CallTimeLineSearch;
use app\models\Support;
use app\services\AsteriskService;
use app\services\CallService;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use Yii;
use yii\filters\AjaxFilter;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

/**
 * Class CallController
 * @package app\controllers
 */
class CallController extends FilteredController
{

    public function behaviors()
    {
        return [
            'ajax' => [
                'class' => AjaxFilter::class,
                'only' => [
                    'update-channel',
                    'save-incoming-data',
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'status',
                        ],
                        'roles' => [
                            RbacHelper::ROLE_ADMIN,
                            RbacHelper::ROLE_SUPER_ADMIN,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'get-status',
                        ],
                        'roles' => ['call.get-status'],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'update-channel',
                        ],
                        'roles' => ['call.set-status'],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'timeline',
                        ],
                        'roles' => ['call.timeline'],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'list',
                        ],
                        'roles' => [
                            RbacHelper::ROLE_SUPER_ADMIN,
                            RbacHelper::ROLE_ADMIN,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'save-incoming-data',
                        ],
                        'roles' => [
                            'call.listener',
                            'call.listener-support',
                            RbacHelper::ROLE_SELLER,
                            RbacHelper::ROLE_SUPPORT,
                            RbacHelper::ROLE_SUPER_ADMIN,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'quality-stat',
                            'ping-stat',
                            'ping-stat-users',
                        ],
                        'roles' => [
                            RbacHelper::ROLE_ADMIN,
                            RbacHelper::ROLE_SUPER_ADMIN,
                        ],
                    ],

                    // everything else is denied
                ],
            ],
        ];


    }


    /**
     * Сохраняет информацию о звонке (телефон, тип, если найден customerId)
     *
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     */
    public function actionSaveIncomingData()
    {
        $phone = \Yii::$app->request->post('phone');
        $type = (int)\Yii::$app->request->post('type');

        $callService = Yii::createObject(CallService::class);

        $this->asJson([
            'success' => $callService->saveIncomingData($phone, $type),
            'phone' => $phone,
            'type' => $type,
        ]);
    }


    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionTimeline(){


        $searchModel = new CallTimeLineSearch(['scenario' => CallTimeLineSearch::SCENARIO_TIME_LINE]);

        $params = (\Yii::$app->request->queryParams);

        $searchModel->load($params);

        if(! $searchModel->validate()){

            $searchModel->errMessage = 'not valid input';

            return $this->render('timeline',
                [
                    'composed' => [],
                    'searchModel' => $searchModel
                ]
            );
        }

        $data = $searchModel->getRawData();

        $composed = $searchModel->composeData($data);

        $byCountry = ArrayHelper::index($composed, null, 'country_id');

        $this->layout = 'call_timeline';

        return $this->render('timeline',
            [
                'composed' => $byCountry,
                'searchModel' => $searchModel,
            ]
        );

    }

    /**
     * @return string
     * @throws \yii\web\ForbiddenHttpException
     * @throws InvalidArgumentException
     */
    public function actionList(): string
    {
        if ( ! RbacHelper::userAllowCallList()) {
            throw new ForbiddenHttpException('You are not allowed for this action');
        }

        $searchModel = new CallsSearch(['scenario' => CallsSearch::SCENARIO_LIST]);

        $users = Support::find()->notAutoCall()->active()
             ->select(['login'])->indexBy('id')->column();

        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
            'users'        => $users,
        ]);
    }

    /**
     * Получает даныые по качеству звонков (в сыром виде)
     *
     * @param string $from
     * @param string $to
     * @param null $userId
     * @return \yii\web\Response
     */
    public function actionQualityStat(string $from, string $to, $userId = null){

        $service = new AsteriskService();

        try{
            $data = $service->getCallQualityStat(['from_date' => $from, 'to_date' => $to,'user_id' => $userId]);

            return $this->asJson($data);
        }

        catch (ClientException $e){

            return $this->asJson(['error' => $e->getMessage()]);
        }
        catch (ServerException $e){
            return $this->asJson(['error' => $e->getMessage()]);
        }
        catch (\Exception $e) {
        }

    }

    /**
     * @param string $from
     * @param string $to
     * @param null $user_id
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionPingStat(string $from, string $to, $user_id = null){

        $service = new AsteriskService();

        if($user_id === 'null'){
            $user_id = null;
        }
        try {
            $data = $service->getUserPingStat(['from_date' => $from, 'to_date' => $to, 'user_id' => $user_id]);

            // если не указан userId,  то вычисляем среднее значение для ping по каждой временной метке
            if($user_id === null){

                $map = ArrayHelper::map($data, 'user_id', 'ping', 'datetime');

                $result = [];

                foreach ($map as $datetime => $dateTimeData) {

                    $item['datetime'] = $datetime;

                    $numArray = array_map('intval', $dateTimeData);

                    $item['ping'] = array_sum($numArray)/ count($dateTimeData);

                    $result[] = $item;
                }

                return $this->asJson($result);

            }
            return $this->asJson($data);
        }
        catch (ClientException $e){

            return $this->asJson(['error' => $e->getMessage()]);
        }
        catch (ServerException $e){
            return $this->asJson(['error' => $e->getMessage()]);
        }

    }

    /**
     * Проверяет наличие данных по заданным парметрам
     * возвращает либо пустой массив, либо массив юзеро, по которым есть данные
     *
     * @param string $from
     * @param string $to
     * @param null $user_id
     * @return \yii\web\Response
     */
    public function actionPingStatUsers(string $from, string $to, $user_id = null)
    {

        $service = new AsteriskService();

        try {
            $data = $service->getUserPingStat(['from_date' => $from, 'to_date' => $to, 'user_id' => $user_id]);

            $map = ArrayHelper::map($data, 'user_id','ping');

            return $this->asJson(array_keys($map));

        } catch (ClientException $e) {

            return $this->asJson(['error' => $e->getMessage()]);
        } catch (ServerException $e) {
            return $this->asJson(['error' => $e->getMessage()]);
        } catch (\Exception $e) {

            return $this->asJson(['error' => $e->getMessage()]);
        }
    }
}
