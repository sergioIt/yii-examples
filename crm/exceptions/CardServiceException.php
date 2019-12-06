<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 25.09.2018, 15:50
 */

namespace app\exceptions;

use yii\base\Exception;

/**
 * Class CardServiceException
 * @package app\exceptions
 */
class CardServiceException extends Exception
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return __CLASS__;
    }
}