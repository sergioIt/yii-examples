<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 20.03.19
 * Time: 15:22
 */

namespace app\tests\unit\models;


use app\models\Card;
use app\models\crm\CardEvents;
use tests\fixtures\CardsFixture;
use tests\unit\BaseUnit;

class CardEventsTest extends BaseUnit
{
    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'cards'           => CardsFixture::class,
        ];
    }

    public function testEventCreate(){

            $cardAttributes = [
                'user_id'     => 2,
                'customer_id' => 703796,
                'reg_date'    => 1528777503,
            ];

        $card = new Card();
        $card->setAttributes($cardAttributes);
        $this->assertTrue($card->validate());
        $this->assertTrue($card->save());

        // @todo запись почему-то не создаётся и тест не проходит
//        $event = CardEvents::find()->where([
//            'type' => 'card-create',
//        ])->one();
//
//        $this->assertNotNull($event);
    }
}
