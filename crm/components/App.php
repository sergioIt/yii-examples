<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 22.05.17
 * Time: 19:20
 */

namespace app\components;

use Yii;

/**
 * Class App
 * @package app\components
 *
 * Application-level component
 *
 */
class App
{
    const ENV_STAGE = 'stage';
    const ENV_PROD = 'prod';
    const ENV_DEV = 'dev';

    /**
     * Get current app version
     * @return string
     */
    public static function getVersion(){

        return '5.25.1';
    }

    /**
     * Получает название окружения
     *
     * @return mixed
     */
    public static function getEnv(){

        return Yii::$app->params['env'];
    }


    /**
     *
     * Check if  table user_bank_books existed and used on trading system
     * @return bool
     */
    public static function userBankBookEnabled(){

        $crmParams = Yii::$app->params['crm'];

        if(! array_key_exists('user_bank_books_enabled', $crmParams)){

            return false;
        }

        return $crmParams['user_bank_books_enabled'];
    }


}
