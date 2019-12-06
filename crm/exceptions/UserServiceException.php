<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 01/07/2019, 17:12
 */

namespace app\exceptions;

use yii\base\Exception;

/**
 * Class UserServiceException
 * @package app\exceptions
 */
class UserServiceException extends Exception
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return __CLASS__;
    }
}
