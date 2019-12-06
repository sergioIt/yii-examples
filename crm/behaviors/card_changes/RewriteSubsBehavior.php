<?php

namespace app\behaviors\card_changes;

use app\models\AppLog;
use app\models\Card;
use app\models\CardChanges;
use Jupiter\BinaryPlatform\Sdk\BinaryPlatformClient;
use yii\db\ActiveRecord;
use \yii\base\Behavior;

/**
 * Created by PhpStorm.
 * User: makryun
 * Date: 23/01/2018
 * Time: 16:14
 *
 * При изменении статуса карточки нужно передавать параметры в iron/siam.
 *
 * Параметры:
 *
 * sub9(Статус звонка) - approve/in progress/decline
 * sub8 - new/recall (если раньше карточки не было или она была в статусе new, то ставим - new. В остальных случаях - recall)
 * sub7 - callcenter (Всегда)
 *
 * url - (app.irontrade.com/app.siamoption.com) /api/rewrite-sub/?
 *
 * так же в user_agent надо передавать два параметра
 * provider: callcenter, user_key: HIIUGHKJHJKH]
 *
 * user_key - > users.user_key в базе
 *
 */
class RewriteSubsBehavior extends Behavior
{
    /**
     * sub7 parameter is always constant
     */
    const SUB7 = 'callcenter';

    /**
     * used in user_agent provider param
     */
    const PROVIDER = 'callcenter';

    /**
     * @var BinaryPlatformClient
     */
    protected $client;

    /**
     * Используется dependency injection container,
     *  передавать в конструктор ничего не надо,
     *  просто создание класса через \Yii::createObject() или \Yii::$container->get()
     *
     * @param BinaryPlatformClient $client
     * @param array $config
     */
    public function __construct(BinaryPlatformClient $client, $config = [])
    {
        $this->client = $client;
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public static function allowedStatuses(): array
    {
        return [
            Card::STATUS_APPROVE     => 'approve',
            Card::STATUS_IN_PROGRESS => 'in progress',
            Card::STATUS_DECLINE     => 'decline',
        ];
    }

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
        ];
    }


    /**
     * @param $event
     *
     * @return bool
     * @throws \Jupiter\BinaryPlatform\Sdk\Exceptions\ResponseException
     * @throws \Jupiter\BinaryPlatform\Sdk\Exceptions\RequestException
     * @throws \Jupiter\BinaryPlatform\Sdk\Exceptions\InvalidAnswerFormatException
     */
    public function afterInsert($event): bool
    {
        /** @var $change CardChanges */
        $change = $event->sender;

        if ( ! $change instanceof CardChanges) {
            return false;
        }

        if ( ! $change->isStatusChange()) {
            return false;
        }

        if ( ! $this->statusAllowed($change->status)) {
            return false;
        }

        $userKey = $this->getUserKey($change);
        $sub7 = $this->getSub7();
        $sub8 = $this->getSub8($change);
        $sub9 = $this->getSub9($change);

        try {
            $this->client->users->rewriteSubs($userKey, self::PROVIDER, $sub7, $sub8, $sub9);
        } catch (\Exception $e) {
            $msg = 'send rewrite sub get request error';
            $msg .= "\n" . 'for customer_id ' . $change->card->customer_id . "\n";
            $msg .= $e->getMessage();

            \Yii::error($msg, AppLog::EXTERNAL_REQUESTS);
        }

        return true;
    }

    /**
     * @param $status
     * @return bool
     */
    protected function statusAllowed($status): bool
    {
        return array_key_exists($status, self::allowedStatuses());
    }

    /**
     * @param $status
     * @return string
     */
    protected function getStatusStringByChangeStatus($status): string
    {
        $statuses = self::allowedStatuses();

        return $statuses[$status];

    }

    /**
     * @return string
     */
    protected function getSub7(): string
    {
        return self::SUB7;
    }

    /**
     * @param CardChanges $change
     *
     * @return string
     */
    protected function getSub8(CardChanges $change): string
    {
        return $change->isFirstStatusChange() ? 'new' : 'recall';
    }

    /**
     * @param CardChanges $change
     *
     * @return string
     */
    protected function getSub9(CardChanges $change): string
    {
        return $this->getStatusStringByChangeStatus($change->status);
    }

    /**
     * @param CardChanges $change
     *
     * @return string
     */
    protected function getUserKey(CardChanges $change): string
    {
        return (string)$change->card->customer->user_key;
    }
}
