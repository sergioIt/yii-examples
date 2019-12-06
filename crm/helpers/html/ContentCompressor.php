<?php
/**
 * Created by PhpStorm.
 * User: makryun
 * Date: 12/01/2018
 * Time: 18:11
 */

namespace app\helpers\html;

use yii\helpers\Html;


/**
 * Class ContentCompressor
 * @package app\helpers\html
 */
class ContentCompressor
{

    const EMAIL_LENGTH_IN_ONE_STRING = 16;

    const BILLING_ID_LENGTH_IN_ONE_STRING = 10;

    /**
     * @param $email
     *
     * @return string
     */
    public static function compressEmail($email){


        if(strlen($email) <= self::EMAIL_LENGTH_IN_ONE_STRING){

            return $email;
        }

        $exploded = explode('@', $email);

        return $exploded[0]. Html::tag('br'). '@'.$exploded[1];
    }

    public static function compressBillingId($id){

        if(strlen($id) <= self::BILLING_ID_LENGTH_IN_ONE_STRING){

            return $id;
        }

        $arr = str_split($id, self::BILLING_ID_LENGTH_IN_ONE_STRING);

        $string = implode(Html::tag('br'), $arr);

        return $string;

    }

}
