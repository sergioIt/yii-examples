<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 26.09.18
 * Time: 17:09
 */

namespace app\exceptions;


use yii\base\Exception;

/**
 * Class CustomersServiceException
 * @package app\exceptions
 */
class CustomersServiceException extends Exception
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return __CLASS__;
    }
}
