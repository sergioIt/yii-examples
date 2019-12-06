<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 26.03.19
 * Time: 16:58
 */

namespace app\exceptions;


use yii\base\Exception;

class AsteriskServiceException extends Exception
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return __CLASS__;
    }

}
