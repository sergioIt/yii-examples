<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 24.07.17
 * Time: 17:46
 */

namespace app\components;


use yii\base\BootstrapInterface;

/**
 * Class LocaleSelector
 * @package app\components
 *
 * define user locale and set locale cookie after ser is logged in
 */
class LocaleSetter implements BootstrapInterface
{

    public function bootstrap($app)
    {
//        $app->user->on(\yii\web\User::EVENT_AFTER_LOGIN,['app\models\Support', 'afterLogin']);
    }
}