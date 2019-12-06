<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 12/10/2018, 14:24
 */

namespace app\behaviors\card;

use app\exceptions\CardException;
use app\models\Card;
use app\models\LogTransitsManager;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\Expression;
use yii\helpers\Json;

/**
 * Class TransitBehavior
 * @package app\behaviors\card
 */
class TransitBehavior extends Behavior
{
    /**
     * @return array
     */
    public function events(): array
    {
        return [
            Card::EVENT_CHANGE_OWNER => 'transit',
        ];
    }

    /**
     * @param Event $event
     *
     * @throws \yii\base\InvalidArgumentException
     * @throws CardException
     */
    public function transit(Event $event)
    {
        /** @var Card $card */
        $card = $event->sender;
        $card->transit = Card::STATE_TRANSIT;

        // add transit log
        $logTransitsManager                = new LogTransitsManager();
        $logTransitsManager->seller_id_old = $card->getOldAttribute('user_id');
        $logTransitsManager->seller_id     = $card->user_id;
        $logTransitsManager->customer_id   = $card->customer_id;

        // from console / tests
        $logTransitsManager->user_id = \Yii::$app->user->isGuest ? null : \Yii::$app->user->getId();

        // set created time 10 seconds ago because on affect customer transit must be created before affect
        $logTransitsManager->created = new Expression('NOW() - interval \'10 seconds\'');

        if ( ! $logTransitsManager->save()) {
            $errMsg = 'Card transit log not saved for customer ' . $card->customer_id . ': ' . Json::encode($logTransitsManager->getErrors());
            throw new CardException($errMsg);
        }
    }
}