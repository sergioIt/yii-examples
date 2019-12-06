<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 14.05.2018, 16:09
 */

namespace app\behaviors\card;

use app\helpers\RbacHelper;
use app\models\AffectedCustomers;
use app\models\Card;
use app\traits\DateTimeTrait;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Expression;

/**
 * Class AffectBehavior
 * @package app\behaviors\card
 */
class AffectBehavior extends Behavior
{
    /**
     * @return array
     */
    public function events(): array
    {
        return [
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
        ];
    }

    /**
     * Перед сохранением (update) card проверяем:
     *  если статус approve и нет записи в affected_customers,
     *  нужно добавить запись и поменять владельца карточки на текущего
     *
     * @param Event $event
     * @return bool
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     * @throws \yii\base\InvalidArgumentException
     */
    public function beforeUpdate($event): bool
    {
        /**
         * @var $card Card
         */
        $card = $event->sender;

        if (!$card instanceof Card) {
            return false;
        }

        if ($card->isApproved() && !$card->hasAffectedCustomer()) {

            $ac = new AffectedCustomers();
            $ac->setAttributes([
                'customer_id' => $card->customer_id,
                'affected' => new Expression('NOW()'),
            ]);

            // костыль для синхронизации верной даты affect
            // если есть запись в customer_basis_reward - берем affected и reset оттуда
            $ac->setAffectedAndResetByCustomerBasisReward();

            if ($ac->validate() && $ac->save()) {
                // change card owner
                if (!$card->hasCustomerReward()) { // костыль, мол только для новых, для старых не надо менять
                    $currentUser = \Yii::$app->user->getId();
                    if (RbacHelper::isUserAnySeller($currentUser)) {
                        $card->user_id = $currentUser;
                    }
                }
                
                return true;
            }

            $message = 'Failed to save AffectionCustomers: customer_id = ' .
                $card->customer_id . ', affected = ' . date(DateTimeTrait::DATE_FORMAT);

            \Yii::error($message, 'affected_customers');

            throw new Exception($message);
        }

        return true;
    }
}
