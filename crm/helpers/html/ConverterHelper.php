<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 20.12.17
 * Time: 13:56
 */

namespace app\helpers\html;


class ConverterHelper
{

    /**
     * @param $val integer
     * @return string
     */
    public static function prepareOperationsTimeFrame($val){

        if($val < 60){
            return (string)$val. ' s';
        }

        return $val / 60 .' min';
    }
}