<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 15.05.19
 * Time: 14:17
 */

namespace app\services;

use app\exceptions\CustomersDataServiceException;
use app\models\Customers;
use app\models\CustomersData;
use yii\base\InvalidArgumentException;
use yii\helpers\Json;

/**
 * Class CustomersDataService
 * @package app\services
 *
 *  класс для синхронизации данных в таблице customers_data
 */
class CustomersDataService
{
    const LOG_CATEGORY = 'customers-data-service';

    const REDIS_KEY_EXPORT_REFILL = 'export_to_crm_user_demo_refill_stats';
    const REDIS_KEY_EXPORT_FILLUP = 'export_to_crm_deposit_form_events';
    const REDIS_KEY_EXPORT_TOURNAMENT_OPERATIONS = 'export_to_crm_tournament_operations';

    const LAST_REFILL_DATE_ATTRIBUTE = 'last_refill_date';
    const LAST_FILLUP_DATE_ATTRIBUTE = 'last_fillup_date';
    const LAST_TOURNAMENT_OPERATION_ATTRIBUTE = 'last_tournament_operation';


    /**
     * массив соответствий атрибоув модели CustomerData и ключей массива данных из редиса
     * @return array
     */
    protected static function mapAttributes()
    {

        return [

            'last_refill_date' => [ 'redis_list' => self::REDIS_KEY_EXPORT_REFILL, 'data_keys' => ['id', 'date_time', 'user_id']],
            'last_fillup_date' =>['redis_list' => self::REDIS_KEY_EXPORT_FILLUP,   'data_keys' => ['id', 'date_time', 'user_id']],
            'last_tournament_operation' => ['redis_list' => self::REDIS_KEY_EXPORT_TOURNAMENT_OPERATIONS, 'data_keys' => ['id', 'open_time', 'user_id']],
        ];
    }

    public static function getList(string $attribute){

        $map = self::getMapForAttribute($attribute);


        $list = $map['redis_list'];

        return $list;
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public static function isMappedAttribute(string $attribute)
    {

        return array_key_exists($attribute, self::mapAttributes());
    }

    /**
     * @param string $attribute
     * @return mixed
     * @throws CustomersDataServiceException
     */
    public static function getMapForAttribute(string $attribute)
    {

        $map = self::mapAttributes();

        if (!array_key_exists($attribute, $map)) {

            throw new CustomersDataServiceException('not allowed attribute to sync:' . $attribute);
        }

        return $map[$attribute];
    }

    /**
     * @param string $attribute
     * @param array $data
     * @throws CustomersDataServiceException
     *
     * @return bool
     */
    public function updateAttribute(string $attribute, array $data)
    {
        $this->checkDataForAttribute($attribute, $data);

        //  если записи вклиентах нет, то ничего альше не делаем, чтобы не нарушать связность
        // да и не получится сохранить из-за foreign key
        if (! Customers::findOne($data['user_id'])) {

           return false;
        }

        if (!$customerData = CustomersData::findOne(['customer_id' => $data['user_id']])) {

            $customerData = new CustomersData();

            $customerData->customer_id = $data['user_id'];
        }

        $customerData->$attribute = $this->getAttributeValue($attribute, $data);

        if (!$customerData->save()) {
            throw new CustomersDataServiceException('Failed saving customer_data #' . $data['id'] . ': ' . Json::encode($customerData->getErrors()));
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param array $data
     * @return bool
     *
     * @throws CustomersDataServiceException
     */
    protected function checkDataForAttribute(string $attribute, array $data)
    {

        if (!self::isMappedAttribute($attribute)) {

            throw new CustomersDataServiceException('not allowed attribute to sync:' . $attribute);
        }

        if (!array_key_exists('id', $data) || !(int)$data['id']) {
            throw new InvalidArgumentException('Key `id` is required and must be integer');
        }

        if (!array_key_exists('user_id', $data) || !(int)$data['user_id']) {
            throw new InvalidArgumentException('Key `user_id` is required and must be integer');
        }

        switch ($attribute) {

            case self::LAST_FILLUP_DATE_ATTRIBUTE:
            case self::LAST_REFILL_DATE_ATTRIBUTE:

                if (!array_key_exists('date_time', $data) || $data['date_time'] === false) {
                    throw new InvalidArgumentException('Key `date_time` is required and should not be empty');
                }

                break;

            case self::LAST_TOURNAMENT_OPERATION_ATTRIBUTE:

                if (!array_key_exists('open_time', $data) || !(int)$data['open_time']) {
                    throw new InvalidArgumentException('Key `open_time` is required and must be integer');
                }

                break;

        }

        return true;

    }

    /**
     * @param string $attribute
     * @param array $data
     * @return mixed|null
     */
    public function getAttributeValue(string $attribute, array $data){

        switch ($attribute) {

            case self::LAST_FILLUP_DATE_ATTRIBUTE:

               return $data['date_time'];

            case self::LAST_REFILL_DATE_ATTRIBUTE:

                return $data['date_time'];

            case self::LAST_TOURNAMENT_OPERATION_ATTRIBUTE:

                return $data['open_time'];

        }

        return null;

    }
}
