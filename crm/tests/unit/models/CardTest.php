<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 14.05.2018, 18:19
 */

namespace tests\models;

use app\models\AffectedCustomers;
use app\models\Card;
use app\models\LogTransitsManager;
use app\models\Payments;
use app\models\Support;
use tests\fixtures\AffectedCustomersFixture;
use tests\fixtures\LogTransitsManagerFixture;
use tests\unit\BaseUnit;
use tests\fixtures\CardsFixture;
use tests\fixtures\PaymentsFixture;
use tests\fixtures\PaymentsSourceFixture;

/**
 * Class CardTest
 * @package tests\models
 */
class CardTest extends BaseUnit
{
//    /** @inheritdoc */
//    public function fixtures(): array
//    {
//        return [
//            'cards'                => CardsFixture::class,
//            'payments'             => PaymentsFixture::class,
//            'payments_source'      => PaymentsSourceFixture::class,
//            'affected_customers'   => AffectedCustomersFixture::class,
//            'log_transits_manager' => LogTransitsManagerFixture::class,
//        ];
//    }
//
//    /**
//     * Add card
//     */
//    public function testInsert()
//    {
//        $cardAttributes = [
//            'user_id'     => 2,
//            'customer_id' => 703795,
//            'reg_date'    => 1528777503,
//        ];
//
//        $card = new Card();
//        $card->setAttributes($cardAttributes);
//        $this->assertTrue($card->validate());
//        $this->assertTrue($card->save());
//    }
//
//    /**
//     * Affect customer (status approve)
//     */
//    public function testAffectCustomer()
//    {
//        $cardId = 102;
//        $this->assertNotNull($card = Card::findOne($cardId));
//        $this->assertFalse($card->hasAffectedCustomer());
//        $this->assertNull($card->affectedCustomer);
//
//        $this->assertEquals(2, $card->user_id);
//        $this->assertEquals(1, Payments::find()->where(['customer_id' => $card->customer_id])->count());
//
//        $card->status = Card::STATUS_IN_PROGRESS;
//        $this->assertTrue($card->validate());
//        $this->assertTrue($card->save());
//        $this->assertFalse($card->hasAffectedCustomer());
//        $this->assertNull($card->affectedCustomer);
//
//        $this->assertEquals(2, $card->user_id);
//        $this->assertEquals(1, Payments::find()->where(['customer_id' => $card->customer_id])->count());
//
//        $card->status = Card::STATUS_APPROVE;
//        $this->assertTrue($card->validate());
//        $this->assertTrue($card->save());
//
//        // console user id = 0
//        //$this->assertEquals(0, $card->user_id);
//        $this->assertEquals(4, Payments::find()->where(['customer_id' => $card->customer_id])->count());
//
//        $this->assertNotNull($card = Card::findOne($cardId));
//        $this->assertTrue($card->hasAffectedCustomer());
//        $this->assertInstanceOf(AffectedCustomers::class, $card->affectedCustomer);
//        $this->assertEquals($card->customer_id, $card->affectedCustomer->customer_id);
//    }
//
//    /**
//     * Test main method transit
//     *
//     * Must change owner and add log in log_transit_manager
//     */
//    public function testTransit()
//    {
//        $cardId         = 101;
//        $currentOwnerId = 2;
//        $newOwnerId     = 4;
//        $this->assertNotNull($card = Card::findOne($cardId));
//        $this->assertEquals($currentOwnerId, $card->user_id);
//        $this->assertNotEquals(Card::STATE_TRANSIT, $card->transit);
//
//        $card->user_id = $newOwnerId;
//        $this->assertTrue($card->save());
//        $this->assertEquals($newOwnerId, $card->user_id);
//        $card = Card::findOne($cardId);
//        $this->assertEquals($newOwnerId, $card->user_id);
//        $this->assertEquals(Card::STATE_TRANSIT, $card->transit);
//
//        $transitLog = LogTransitsManager::find()->where(['customer_id' => $card->customer_id])->orderBy(['created' => SORT_DESC])->one();
//        $this->assertNotNull($transitLog);
//        $this->assertEquals($currentOwnerId, $transitLog->seller_id_old);
//        $this->assertEquals($newOwnerId, $transitLog->seller_id);
//    }
//
//    /**
//     *
//     */
//    public function testSaveExisted(){
//
//        $card = Card::findOne(['customer_id' => 106]);
//
//        $card->status = Card::STATUS_NOT_ON_PHONE;
//
//        $this->assertTrue($card->validate());
//
//        $this->assertTrue($card->save());
//    }
//
//    /**
//     * Проверяем 2 кейса:
//     *  - транзит не происходит, если владелец карточки не уволен
//     *  - транзит происходит, если владелец карточки уволен
//     */
//    public function testTransitIfSupportFired()
//    {
//        # оунер не уволен, метод вернет false, ничего не изменится
//
//        $this->assertNotNull($card = Card::findOne(102));
//        $currentOwner = $card->user_id;
//        $this->assertNotNull($newOwner = Support::findOne(3));
//
//        $this->assertFalse($card->transitIfSupportFired($newOwner->id));
//        $this->assertEquals($card->user_id, $currentOwner);
//
//        # -----------------------
//
//        # оунер уволен, транзит должен случится, метод вернуть true
//        $this->assertNotNull($card = Card::findOne(104));
//        $currentOwner = $card->user_id;
//        $this->assertTrue($card->transitIfSupportFired($newOwner->id));
//        $this->assertEquals($card->user_id, $newOwner->id);
//
//        # должна быть запись в транзит логе
//        $this->assertNotNull($transit = LogTransitsManager::findOne([
//            'customer_id' => $card->customer_id,
//            'seller_id_old' => $currentOwner,
//            'seller_id' => $newOwner->id,
//        ]));
//
//    }

}
