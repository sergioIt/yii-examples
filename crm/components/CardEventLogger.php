<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 19.04.2018, 15:16
 */

namespace app\components;

use app\controllers\CardController;
use app\controllers\CustomerController;
use app\events\CardEvent;
use app\helpers\RbacHelper;
use app\models\Card;
use app\models\crm\CardEvents;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class CardEventLogger
 * @package app\components
 */
class CardEventLogger implements BootstrapInterface
{
    const LOG_CATEGORY = 'event:card';

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        Event::on(
            CustomerController::class,
            CustomerController::EVENT_CARD_VIEW,
            [$this, 'cardView']
        );

        Event::on(
            CardController::class,
            CardController::EVENT_CARD_SEARCH,
            [$this, 'cardSearch']
        );

        Event::on(
            Card::class,
            Card::EVENT_CREATE,
            [$this, 'cardCreate']
        );
    }

    /**
     * @param CardEvent $event
     */
    public function cardView(CardEvent $event) {

        if (!RbacHelper::isUserAnySeller(\Yii::$app->getUser()->getId())) {
            return;
        }

        $this->sendLog($event);
    }

    /**
     * @param CardEvent $event
     */
    public function cardSearch(CardEvent $event)
    {
        if (!RbacHelper::isUserAnySeller(\Yii::$app->getUser()->getId())) {
            return;
        }

        $this->sendLog($event);
    }

    /**
     * @param CardEvent $event
     */
    public function cardCreate(CardEvent $event)
    {
        $this->sendLog($event);
    }

    /**
     * Логирует событие
     *
     * @param CardEvent $event
     */
    protected function sendLog(CardEvent $event)
    {

        try{

            $cardEvent = new CardEvents();
            $cardEvent->user_id = \Yii::$app->getUser()->getId();
            $cardEvent->customer_id = $event->customerId;
            $cardEvent->type = $event->name;

            $referrer = parse_url(\Yii::$app->getRequest()->getReferrer(), PHP_URL_PATH);

            $data = ['referrer' => $referrer];

            if( $event->additionalData !== null){

                $data =  ArrayHelper::merge(['referrer' => $referrer], $event->additionalData);

            }

            $cardEvent->data = $data;

            if(! $cardEvent->save()){

                \Yii::error('error saving cards events log: '. Json::encode($cardEvent->getErrors()));
            }
        }
        catch (Exception $e){

            \Yii::error('Exception while saving cards events log: '. $e->getMessage());
        }

    }
}
