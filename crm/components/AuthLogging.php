<?php
namespace app\components;

use yii\base\BootstrapInterface;

/**
 * Class LoginsLogging
 * @package app\components
 */
class AuthLogging implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->user->on(\yii\web\User::EVENT_AFTER_LOGIN,['app\models\AuthLog', 'login']);
        $app->user->on(\yii\web\User::EVENT_BEFORE_LOGOUT,['app\models\AuthLog', 'logout']);
    }
}