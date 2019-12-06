<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25.09.18
 * Time: 18:10
 */

namespace app\exceptions;


use yii\base\Exception;

/**
 * Class CustomersTypeServiceException
 * @package app\exceptions
 */
class CustomersTypeServiceException extends Exception
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return __CLASS__;
    }
}
