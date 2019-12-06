<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 02/10/2018, 13:38
 */

namespace tests\models;

use app\models\Card;
use app\models\CardChanges;
use app\models\CardComment;
use tests\fixtures\CardChangesFixture;
use tests\unit\BaseUnit;

/**
 * Class CardChangesTest
 * @package tests\models
 */
class CardChangesTest extends BaseUnit
{
    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'cards_changes' => CardChangesFixture::class,
        ];
    }

    /**
     * Get by id
     */
    public function testGetById()
    {
        $this->assertNotNull($card = Card::findOne(102));
        $cc = CardChanges::find()->where([
            'card_id' => $card->id,
            'type' => CardChanges::TYPE_STATUS_CHANGE,
            'status'  => Card::STATUS_APPROVE,
        ])->orderBy(['id' => SORT_DESC])->one();

        $this->assertNotNull($cc);
    }

    /**
     * Test auto approve (add record)
     */
//    public function testAutoApprove()
//    {
//        $this->assertNotNull($card = Card::findOne(102));
//
//        $cc = CardChanges::find()->where([
//            'card_id' => $card->id,
//            'type' => CardChanges::TYPE_AUTO_APPROVE,
//        ])->one();
//
//        $this->assertNull($cc);
//        $this->assertTrue(CardChanges::autoApprove($card->id, false));
//
//        $cc = CardChanges::find()->where([
//            'card_id' => $card->id,
//        ])->orderBy(['created' => SORT_DESC])->one();
//
//        $this->assertNotNull($cc);
//        $this->assertEquals($cc->type, CardChanges::TYPE_AUTO_APPROVE);
//        $this->assertEquals($cc->card_id, $card->id);
//        $this->assertNull($cc->user_id);
//
//        $this->assertTrue(CardChanges::autoApprove($card->id, true));
//
//        $cc = CardChanges::find()->where([
//            'card_id' => $card->id,
//        ])->orderBy(['created' => SORT_DESC])->one();
//
//        $this->assertEquals($cc->user_id, \Yii::$app->user->getId());
//    }

    /**
     * проверяет поле recall_date в изменениях карточки,
     * для которой сначала выставляется дата перезвона, а затем затирается
     * должно появится две записи - с датой перезвона и с пустой датой перезвона
     */
//    public function testSaveRecallDate(){
//
//        $card = Card::findOne(106);
//
//        $card->recall_date = '2019-03-16 20:00:28';
//
//        $card->save();
//
//        /**
//         * @var $lastChange CardChanges
//         */
//        $lastChange = CardChanges::find()->where([
//            'card_id' => $card->id,
//        ])->orderBy(['created' => SORT_DESC])->one();
//
//        $this->assertEquals($lastChange->type, CardChanges::TYPE_DATE_RECALL_CHANGE);
//        $this->assertEquals($lastChange->recall_date, $card->recall_date);
//        $this->assertNotNull($lastChange->recall_date);
//
//        // затираем дату перезовна в карточке и проверяем, что в полседнем изменении она тоже пустая
//        $card->recall_date = null;
//
//        $card->save();
//
//        /**
//         * @var $lastChange CardChanges
//         */
//        $lastChange = CardChanges::find()->where([
//            'card_id' => $card->id,
//        ])->orderBy(['created' => SORT_DESC])->one();
//
//        $this->assertEquals($lastChange->type, CardChanges::TYPE_DATE_RECALL_CHANGE);
//        $this->assertNull($lastChange->recall_date);
//    }

    /**
     * проверяет, что после сохранения комента создаётся изменение  соответствующего типа, привязанное к владельцу комента
//     */
//    public function testCardChangeOnNewComment(){
//
//        $card = Card::findOne(106);
//
//        $this->assertNotNull($card);
//
//        $comment = new CardComment();
//        $comment->card_id = $card->id;
//        $comment->user_id = 6;
//        $comment->text = 'test from seller 6';
//
//        $this->assertTrue($comment->save());
//        /**
//         * @var $lastChange CardChanges
//         */
//        $lastChange = CardChanges::find()->where([
//            'card_id' => $card->id,
//        ])->orderBy(['created' => SORT_DESC])->one();
//
//        $this->assertEquals($lastChange->type, CardChanges::TYPE_COMMENT_ADD);
//        $this->assertEquals(6, $lastChange->user_id);
//    }
}
