<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 15.05.2018, 15:54
 */

namespace tests\models;

use app\models\AffectedCustomers;
use tests\fixtures\CardsFixture;
use tests\unit\BaseUnit;
use tests\fixtures\AffectedCustomersFixture;
use tests\fixtures\PaymentsFixture;

/**
 * Class AffectedCustomersTest
 * @package tests\models
 */
class AffectedCustomersTest extends BaseUnit
{
    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'cards' => CardsFixture::class,
            'affectedCustomers' => AffectedCustomersFixture::class,
            'payments' => PaymentsFixture::class,
        ];
    }

    /**
     * Get by ID
     */
    public function testGetById()
    {
        $this->assertNotNull($affectedCustomer = AffectedCustomers::findOne(101));
        $this->assertNull($affectedCustomer = AffectedCustomers::findOne(999));
    }

    /**
     * Get first payment
     */
    public function testGetFirstPayment()
    {
        $this->assertNotNull($affectedCustomer = AffectedCustomers::findOne(101));
        $this->assertNotNull($affectedCustomer->firstPayment);
        $this->assertEquals(5000.00, $affectedCustomer->firstPayment->amount);

        $this->assertNotNull($affectedCustomer = AffectedCustomers::findOne(102));
        $this->assertNull($affectedCustomer->firstPayment);
    }

    /**
     * Reset date
     */
    public function testReset()
    {
        $this->assertNotNull($affectedCustomer = AffectedCustomers::findOne(104));
        $this->assertNull($affectedCustomer->reset);
        $this->assertFalse($affectedCustomer->isReset());
        $this->assertTrue($affectedCustomer->reset());
        $this->assertNotNull($resetDate = $affectedCustomer->reset);
        $this->assertTrue($affectedCustomer->isReset());
        $this->assertFalse($affectedCustomer->reset());
        $this->assertEquals($resetDate, $affectedCustomer->reset);
    }
}
