<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 12/10/2018, 12:08
 */

namespace app\exceptions;

use yii\base\Exception;

/**
 * Class CardException
 * @package app\exceptions
 */
class CardException extends Exception
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return __CLASS__;
    }
}