<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 19/11/2018, 14:06
 */

namespace app\events;

use yii\base\Event;

/**
 * Class CounterEvent
 * @package app\events
 */
class CounterEvent extends Event
{
    /**
     * @var
     */
    public $userId;

    /**
     * CounterEvent constructor.
     *
     * @param int $userId
     * @param array $config
     */
    public function __construct(int $userId, array $config = [])
    {
        parent::__construct($config);
        $this->userId = $userId;
    }
}