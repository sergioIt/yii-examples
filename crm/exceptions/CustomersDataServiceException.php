<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 15.05.19
 * Time: 14:28
 */

namespace app\exceptions;


use yii\base\Exception;

class CustomersDataServiceException extends Exception
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return __CLASS__;
    }
}
