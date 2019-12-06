<?php
/**
 * Created by PhpStorm.
 * User: makryun
 * Date: 04/10/2018
 * Time: 12:59
 */

namespace app\exceptions;


use yii\base\Exception;

/**
 * Class NextCustomerServiceException
 * @package app\exceptions
 */
class NextCustomerServiceException extends Exception
{

    /**
     * @return string
     */
    public function getName(): string
    {
        return __CLASS__;
    }
}