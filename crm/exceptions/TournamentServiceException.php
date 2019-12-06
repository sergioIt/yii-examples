<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 16.05.19
 * Time: 12:49
 */

namespace app\exceptions;


use yii\base\Exception;

/**
 * Class TournamentServiceException
 * @package app\exceptions
 */
class TournamentServiceException extends Exception
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return __CLASS__;
    }
}
