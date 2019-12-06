<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25.09.18
 * Time: 16:46
 */

namespace app\exceptions;


use yii\base\Exception;

/**
 * Class AsteriskApiException
 * @package app\exceptions
 */
class AsteriskApiException extends Exception
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return __CLASS__;
    }
}
