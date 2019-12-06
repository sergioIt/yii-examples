<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 15.05.19
 * Time: 14:23
 */

namespace app\commands;


use app\components\ConsoleController;
use app\exceptions\CustomersDataServiceException;
use app\services\CustomersDataService;
use yii\base\Exception;
use yii\console\ExitCode;
use yii\helpers\Json;
use yii\redis\Connection;

/**
 * Class CustomerDataController
 * @package app\commands
 */
class CustomerDataController extends ConsoleController
{
    const LOG_CATEGORY = 'console-customers-data';

    /**
     *
     * апдейтит поле customers_data.last_refill_date
     *
     * @param $attribute string
     * @throws CustomersDataServiceException
     * @throws Exception
     *
     * @return int
     */
    public function actionSyncAttribute(string $attribute){

        $list = CustomersDataService::getList($attribute);

        /** @var Connection $redis */
        $redis = \Yii::$app->redis;

        if (!$redis->llen($list)) { // если пустой список
            return ExitCode::OK;
        }

        $service = new CustomersDataService();

        while ($item = $redis->lpop($list)) {


            $data = Json::decode($item);

            try {

               $update = $service->updateAttribute($attribute, $data);

                if($update){
                    $this->log('Customer #' . $data['user_id'] . '; attribute: '.$attribute.'; redis data: ' . Json::encode($data));
                }

            } catch (CustomersDataServiceException $e) {
                $this->error($e->getMessage());
                continue;
            }
            catch (Exception $e){

                $this->error($e->getMessage());
                continue;
            }

        }

        return ExitCode::OK;

    }


    /**
     * последовательно синхронизирует поля
     * customers_data.last_refill_date,
     * customers_data.last_fillup_date,
     * customers_data.last_tournament_operation
     */
    public function actionSyncAll(){

        $this->runAction('sync-attribute', ['last_refill_date']);
        $this->runAction('sync-attribute', ['last_fillup_date']);
        $this->runAction('sync-attribute',['last_tournament_operation']);

        return ExitCode::OK;
    }
}
