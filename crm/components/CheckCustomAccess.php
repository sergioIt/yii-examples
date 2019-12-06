<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 12.09.17
 * Time: 13:16
 */

namespace app\components;

use yii\base\BootstrapInterface;

/**
 * Class CheckCustomAccess
 * @package app\components
 */
class CheckCustomAccess implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->user->on(\yii\web\User::EVENT_AFTER_LOGIN,['app\models\crm\AccessFilter', 'processCustomAccess']);
    }
}
