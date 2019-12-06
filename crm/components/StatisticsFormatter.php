<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 29.05.17
 * Time: 15:50
 */

namespace app\components;

/**
 * Class StatisticsFormatter
 * Perform customer statistics finance values formatting depending on local crm rules
 *
 * @package app\components
 */
class StatisticsFormatter
{

    /**
     * @param $val
     * @return string
     */
    public static function formatValue($val){

        if($val === null){

            return '0';
        }

        if($val === 0){

            return '0';
        }

        $float = (float)$val + 0;
        $int = (int)$val;

        // чтобы возвращалось '0' вместо '0,00'
        if($float == $int && $int === 0){
            return '0';
        }

        // дробная часть всегда = 2
        return number_format((float)$val, 2, ',', ' ');
    }
}
