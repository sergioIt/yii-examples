<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 29.03.19
 * Time: 19:08
 */

namespace app\exceptions;


use yii\base\Exception;

/**
 * Class CustomerQueryServiceException
 * @package app\exceptions
 */
class CustomerQueryServiceException extends Exception
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return __CLASS__;
    }
}
