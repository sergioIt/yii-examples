<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 05.06.2018, 17:36
 */

namespace tests\models;


use app\models\Card;
use app\models\LogTransitsManager;
use tests\unit\BaseUnit;
use tests\fixtures\CardsFixture;
use tests\fixtures\LogTransitsManagerFixture;

class LogTransitsManagerTest extends BaseUnit
{

    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'cards'              => CardsFixture::class,
            'logTransitsManager' => LogTransitsManagerFixture::class,
        ];
    }

    /**
     * Rollback transit by customer_id
     */
    public function testRollback()
    {
        $customerId = 101;

        $this->assertNotNull($transit = LogTransitsManager::find()->where(['customer_id' => $customerId])->orderBy(['created' => SORT_DESC])->one());
        $this->assertTrue($transit->rollback());
        $this->assertNotNull($card = Card::find()->where(['customer_id' => $customerId])->one());
        $this->assertEquals(3, $card->user_id);

        $this->assertNotNull($transit = LogTransitsManager::find()->where(['customer_id' => $customerId])->orderBy(['created' => SORT_DESC])->one());
        $this->assertTrue($transit->rollback());
        $this->assertNotNull($card = Card::find()->where(['customer_id' => $customerId])->one());
        $this->assertEquals(2, $card->user_id);

        $this->assertNull($transit = LogTransitsManager::find()->where(['customer_id' => $customerId])->one());
    }
}
