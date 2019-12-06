<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 03.09.2018, 16:54
 */

namespace app\components\api;

use app\models\Customer;

/**
 * Class SmsApiFactory
 * @package app\components\api
 */
class SmsApiFactory
{
    /**
     * @param Customer $customer
     *
     * @return SmsApi
     */
    public static function makeSmsSender(Customer $customer): SmsApi
    {
        $currency = $customer->currency;
        $phone = $customer->phone;

        // Для Тайланда валюта уникальна и первична
        // для тайланда используется сервисв plivosms
        if (strtoupper($currency) == 'THB') {
            return \Yii::$app->plivosms;
        }

        // Первичной считаем информацию по коду международного номера
        if ($phone[0] == '+') {
            $phone = substr($phone, 1);
        }

        if ($phone) {
            if (substr($phone, 0, 2) == '63') {
                return \Yii::$app->itexmosms;
            }

            if (substr($phone, 0, 2) == '84') {
                return \Yii::$app->plivosms;
                #return \Yii::$app->vhtsms;
            }
        }

        // Универсальный сервис
        return \Yii::$app->plivosms;

    }
}
