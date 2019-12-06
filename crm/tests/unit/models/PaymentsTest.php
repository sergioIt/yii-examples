<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 15.05.2018, 16:58
 */

namespace tests\models;

use app\models\Payments;
use tests\unit\BaseUnit;
use tests\fixtures\PaymentsFixture;

/**
 * Class PaymentsTest
 * @package tests\models
 */
class PaymentsTest extends BaseUnit
{
    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'payments' => PaymentsFixture::class,
        ];
    }

    /**
     * Check model
     */
    public function testGetById()
    {
        $this->assertNotNull($payment = Payments::findOne(1));
        $this->assertTrue($payment->isApproved());
        $this->assertTrue($payment->isFirst());

        $this->assertNotNull($payment = Payments::findOne(3));
        $this->assertFalse($payment->isApproved());
        $this->assertFalse($payment->isFirst());
    }
}
