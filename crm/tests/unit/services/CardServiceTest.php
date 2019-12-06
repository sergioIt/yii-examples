<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 21.09.2018, 14:12
 */

namespace tests\services;

use app\helpers\HotKeyHelper;
use app\helpers\param\BonusSystemParam;
use app\models\AffectedCustomers;
use app\models\Card;
use app\models\CardChanges;
use app\services\CardService;
use app\tests\fixtures\DeclineReasonFixture;
use Exception;
use tests\fixtures\CardChangesFixture;
use tests\fixtures\CardsFixture;
use tests\fixtures\CustomersFixture;
use tests\unit\BaseUnit;
use yii\db\Expression;

/**
 * Class CardServiceTest
 * @package tests\services
 */
class CardServiceTest extends BaseUnit
{
    /**
     * @var CardService
     */
    protected $service;

    /* @inheritdoc */
    protected function setUp()
    {
        parent::setUp();
        $this->service = new CardService();
    }

    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'cards'            => CardsFixture::class,
            'cards_changes'    => CardChangesFixture::class,
            'customers_synced' => CustomersFixture::class,
            'decline_reason' => DeclineReasonFixture::class,
        ];
    }

    /**
     * @dataProvider autoApproveDataProvider
     *
     * @param int $case
     * @param int $customerId
     */
//    public function testAutoApprove(int $case, int $customerId)
//    {
//        switch ($case) {
//            case 0: // not found
//            case 1: // card status !== IN_PROGRESS
//            case 2: // card's owner is fired
//                $this->assertFalse($this->service->autoApproveByCustomerId($customerId));
//
//                break;
//            case 3; // ok
//                $card = Card::find()->where(['customer_id' => $customerId])->one();
//                $this->assertNotNull($card);
//                $this->assertEquals($card->status, Card::STATUS_IN_PROGRESS);
//
//                $ac = AffectedCustomers::find()->where(['customer_id' => $customerId])->one();
//                $this->assertNull($ac);
//
//                $approveDate = '2018-07-07 12:12:13';
//
//                // false because IN_PROGRESS log not found
//                $this->assertFalse($this->service->autoApproveByCustomerId($customerId, $approveDate));
//
//                $cc = new CardChanges();
//                $cc->setAttributes([
//                    'created' => new Expression('NOW() - INTERVAL \'' . BonusSystemParam::getAffectedPeriod() . ' hours\' - INTERVAL \'5 minutes\''),
//                    'card_id' => $card->id,
//                    'user_id' => $card->user_id,
//                    'type'    => CardChanges::TYPE_STATUS_CHANGE,
//                    'status'  => Card::STATUS_IN_PROGRESS,
//                ]);
//                $this->assertTrue($cc->save());
//
//                // false because IN_PROGRESS set > 72h ago
//                $this->assertFalse($this->service->autoApproveByCustomerId($customerId, $approveDate));
//
//                $cc->created = new Expression('NOW() - INTERVAL \'' . BonusSystemParam::getAffectedPeriod() . ' hours\' + INTERVAL \'5 minutes\'');
//                $this->assertTrue($cc->save());
//
//                // true because IN_PROGRESS set < 72h ago
//                $this->assertTrue($this->service->autoApproveByCustomerId($customerId, $approveDate));
//
//                $card = Card::find()->where(['customer_id' => $customerId])->one();
//                $this->assertEquals($card->status, Card::STATUS_APPROVE);
//
//                $ac = AffectedCustomers::find()->where(['customer_id' => $customerId])->one();
//                $this->assertNotNull($ac);
//                $this->assertEquals($ac->customer_id, $customerId);
//                $this->assertEquals($ac->affected, $approveDate);
//
//                break;
//        }
//    }

    /**
     * @return array
     */
    public function autoApproveDataProvider(): array
    {
        return [
            [0, 999], // not found
            [1, 102], // status !== IN_PROGRESS
            [2, 104], // fired
            [3, 103], // ok
        ];
    }

    /**
     * @dataProvider updateDataProvider
     *
     * @param $case
     * @param $customerId
     * @param $data
     */
    public function testUpdateCard($case, $customerId, $data)
    {
        switch ($case) {
            case 0: // validate exception (not found card by customer id)
                $this->expectException(Exception::class);
                $this->service->updateCardByCustomerId($customerId, $data);

                break;
            case 1:
            case 2:
                $card = Card::find()->where(['customer_id' => $customerId])->one();
                $this->assertNotNull($card);
                $this->assertCount(1, $data);

                $updatedRows = $this->service->updateCardByCustomerId($customerId, $data);
                $this->assertEquals(0, $updatedRows);

                break;
            case 3:
                $this->assertCount(1, $data);

                $updatedRows = $this->service->updateCardByCustomerId($customerId, $data);
                $this->assertEquals(1, $updatedRows);

                $card = Card::find()->where(['customer_id' => $customerId])->one();
                $this->assertNotNull($card);

                break;
        }
    }

    /**
     * @return array
     */
    public function updateDataProvider(): array
    {
        return [
            [
                0, // case
                999, // not found
                [], // indifferently
            ],
            [
                1,
                101,
                ['undefined_attribute' => 12345],
            ],
            [
                2,
                101,
                ['not_existed_attribute' => 1],
            ],
            [
                3,
                101,
                ['status' => 3],
            ],
        ];
    }

    /**
     * TODO в этом действии используется обе таблицы customers: в самом методе - db1, при сохрании card changes - db2
     */
//    public function testAutoDeclineByHotKeys()
//    {
//        $this->assertFalse($this->service->autoDeclineByHotKeys(8888)); # такого кастомера нет
//        $this->assertFalse($this->service->autoDeclineByHotKeys(105)); # customer.real = 1
//
//        # соберем модель, и будем обновлять всякое,
//        # чтобы не плодить записи в фикстурах из-за пары пар-ров
//        $this->assertNotNull($card = Card::findOne(109));
//
//
//        $this->assertFalse($this->service->autoDeclineByHotKeys($card->customer_id)); # card.status = decline
//        $card->status = Card::STATUS_FAKE;
//        $this->assertTrue($card->save());
//        $this->assertFalse($this->service->autoDeclineByHotKeys($card->customer_id)); # card.status = fake
//
//        $card->status = Card::STATUS_NEW;
//        # сымитируем критич. кол-во хоткнопок
//        $card->hot_keys = [HotKeyHelper::TYPE_NO_REPLY => CardService::HOT_KEYS_AUTODECLINE_CRITICAL_SUM_VALUE + 1];
//
//        # а при сохранении уже будет вызван autoDeclineByHotKeys в afterSave()
//        $this->assertTrue($card->save());
//
//        # так что просто проверим статус
//        $card->refresh();
//        $this->assertEquals($card->status, Card::STATUS_DECLINE);
//    }


    /**
     *
     */
//    public function testProcessAutoDecline(){
//
//        $card = Card::findOne(101);
//
//        $this->assertInstanceOf(Card::class, $card);
//
//        $this->assertTrue(CardService::processAutoDecline($card->customer_id));
//
//        $card->refresh();
//
//        $this->assertEquals(Card::STATUS_DECLINE, $card->status);
//        $this->assertEquals('autodecline. Banned', $card->declineReason->reason);
//
//
//        /**
//         * @var $lastChange CardChanges
//         */
//        $lastChange = CardChanges::find()->where([
//            'card_id' => $card->id,
//        ])->orderBy(['created' => SORT_DESC])->one();
//
//      $this->assertEquals(114, $lastChange->user_id);
//      $this->assertEquals(CardChanges::TYPE_STATUS_CHANGE, $lastChange->type);
//      $this->assertEquals(Card::STATUS_DECLINE, $lastChange->status);
//
//    }
}
