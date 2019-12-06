<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25.03.19
 * Time: 13:01
 */

namespace app\tests\unit\helpers;


use app\helpers\AsteriskApiV3Helper;
use app\helpers\param\ExternalCallEngineParam;
use app\models\Card;
use app\models\CardChanges;
use app\models\CardComment;
use app\models\Customer;
use app\models\Customers;
use tests\fixtures\CardsFixture;
use tests\fixtures\CustomerFixture;
use tests\fixtures\CustomersFixture;
use tests\unit\BaseUnit;
use yii\base\Exception;

/**
 * Class AsteriskApiHelper
 * @package app\tests\unit\helpers
 */
class AsteriskApiHelperTest extends BaseUnit
{

    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'cards'           => CardsFixture::class,
            'customers'           => CustomersFixture::class,
            'customer'           => CustomerFixture::class,
        ];
    }

    /**
     *
     */
//    public function testUpdateNotPhone(){
//        /**
//         * случай клиента с существующей карточкой (привязанной не к робоколу) и существующим клиентом
//         */
//        $customerId = 106;
//
//        $card = Card::findOne($customerId);
//        $customer = Customers::findOne($customerId);
//
//        $this->assertInstanceOf(Customers::class, $customer);
//        $this->assertInstanceOf(Card::class, $card);
//
//        $helper = new AsteriskApiV3Helper();
//
//        $helper->customer_id = $customer->id;
//        $helper->currency =  $customer->currency;
//        $helper->autoCallUserId =  ExternalCallEngineParam::getAutoCallUserByCurrency($customer->currency);
//
//        $this->assertEquals(115, $helper->autoCallUserId);
//        // ожидаем, что весь процесс сделан и вернул true
//        $this->assertTrue($helper->updateNotOnPhone());
//        // обновляем атрибуты карточки, чтобы проверить поле hot_keys
//        $card->refresh();
//
//        $this->assertNotNull($card->hot_keys);
//        // проверяем, что сумма нажатий hot_keys стала единице
//        $this->assertEquals(1, $card->getHotKeysSum());
//        // проверяем, что в процессе updateNotOnPhone было создано одно изменение карточки
//
//        // проверяем, что в процессе updateNotOnPhone было создано одно изменение
//        $this->assertEquals(1, count($card->changes));
//        // проверяем, что в процессе updateNotOnPhone был создан один коммент
//        $this->assertEquals(1, count( $card->comments));
//
//        /**
//         * @var $comment CardComment
//         */
//        $comment = CardComment::find()->where(['card_id' => $card->id])->one();
//
//        $this->assertEquals(115, $comment->user_id);
//        $this->assertEquals('not on phone', $comment->text);
//
//        /**
//         * @var $change CardChanges
//         *
//         * логика создания изменения после создания комента такая
//         *  $change->user_id = Yii::$app->user->getId() ?? $this->user_id;
//         *
//         * то есть, если текущуй юзер есть, то 1) изменение записывается на него
//         * иначе - 2) на владельца комента
//         *
//         * так как updateNotOnPhone происходит всегда для незалогиненого юзера,
//         * то срабатывает случай 2),
//         *
//         * но как это протестировать здесь непонятно - потому что
//         */
//        $change = CardChanges::find()->where(['card_id' => $card->id])->one();
//
////         проверяем, что изменение записано на юзера робокол
//        $this->assertEquals(115, $change->user_id);
//
//        /**
//         * @var $comment CardComment
//         */
//        $comment = CardComment::find()->where(['card_id' => $card->id])->one();
//
//        $this->assertEquals(115, $comment->user_id);
//        $this->assertEquals('not on phone', $comment->text);
//
//    }


}
