<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 01/10/2018, 16:51
 */

namespace app\components;

use yii\base\BaseObject;

/**
 * Base Service Class
 * @package app\components
 */
class Service extends BaseObject
{
    /**
     * @see Service::log()
     */
    const LOG_CATEGORY = 'service';

    /**
     * @param $message
     */
    public function log(string $message)
    {
        \Yii::info(static::class . ': ' . $message, static::LOG_CATEGORY);
    }

    public function error(string $message)
    {
        \Yii::error(static::class . ': ' . $message, static::LOG_CATEGORY);
    }
}