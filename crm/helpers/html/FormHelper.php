<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 19.12.17
 * Time: 14:40
 */

namespace app\helpers\html;
use yii\bootstrap\Html;

/**
 * Class FormHelper
 * @package app\helpers\html
 */
class FormHelper
{


    /**
     * @param array $channel
     * @return string
     */
    public static function callStatusMultiplierInput(array $channel){

       return Html::input('number', 'call_set_multiplier', $channel['multiplier'],
            [
                'class' => 'number call_status_input_multiplier',
                'data-channel_id' => $channel['id'],
                'min' => 1,
                'max' => 5
            ]);

    }

    /**
     * @param array $channel
     *
     * @return string
     */
    public static function callStatusMaxChannelsInput(array  $channel){

        return Html::input('number', 'call_set_max_channels', $channel['max_channels'],
            [
                'class' => 'number call_status_input_max_channels',
                'data-channel_id' => $channel['id'],
                'min' => 0,
                'max' => 20
            ]);

    }
}
