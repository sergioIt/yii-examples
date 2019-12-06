<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 16.05.2018, 16:03
 */

namespace tests\models;

use app\models\Payment;
use tests\unit\BaseUnit;
use tests\fixtures\PaymentsSourceFixture;

/**
 * Class PaymentsSourceTest
 * @package tests\models
 */
class PaymentsSourceTest extends BaseUnit
{
    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'payments_source' => PaymentsSourceFixture::class,
        ];
    }

    public function testGetById()
    {
        $this->assertNotNull($payments = Payment::findOne(3981227));
        $this->assertNull($payments = Payment::findOne(1));
    }
}
