<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 19/11/2018, 13:52
 */

namespace app\events;

use yii\base\Event;

/**
 * Class CustomerEvent
 * @package app\events
 */
abstract class CustomerEvent extends Event
{
    /**
     * @var int
     */
    public $customerId;

    /**
     * @var array
     */
    public $additionalData;
}
