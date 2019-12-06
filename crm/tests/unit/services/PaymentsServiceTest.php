<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 11.05.2018, 19:00
 */

namespace tests\services;

use app\models\Payments;
use app\services\PaymentsService;
use tests\unit\BaseUnit;
use tests\fixtures\PaymentsFixture;
use tests\fixtures\PaymentsSourceFixture;
use yii\base\Exception;
use yii\base\InvalidArgumentException;

/**
 * Class PaymentsService
 * @package tests\services
 */
class PaymentsServiceTest extends BaseUnit
{
    /**
     * @var PaymentsService
     */
    protected $service;

    /* @inheritdoc */
    protected function setUp()
    {
        parent::setUp();
        $this->service = new PaymentsService();
    }

    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'payments'        => PaymentsFixture::class,
            'payments_source' => PaymentsSourceFixture::class,
        ];
    }

    /**
     * @dataProvider updateDataProvider
     * @param $i
     * @param $data
     */
    public function testUpdatePayment($i, $data)
    {
        switch ($i) {
            case 0: // validate exception
                $this->expectException(Exception::class);
                $this->service->updatePayment($data);

                break;
            case 1: // success insert #555155
            case 5: // and #555156
                $this->assertNull(Payments::findOne($data['id']));
                $this->assertCount(8, $this->service->updatePayment($data));
                $this->assertNotNull($payment = Payments::findOne($data['id']));
                $this->assertInstanceOf(Payments::class, $payment);
                $this->assertEquals($data['id'], $payment->id);
                $this->assertEquals($data['amount'], $payment->amount);
                $this->assertEquals(0, $payment->is_first);
                $this->assertNotNull($payment->changed);

                break;

            case 2: // success update #2
                $this->assertNotNull($payment = Payments::findOne($data['id']));
                $this->assertNotEquals($data['amount'], $payment->amount);
                $this->assertNotEquals($data['is_first'], $payment->is_first);
                $this->assertNull($payment->changed);

                $this->assertCount(2, $this->service->updatePayment($data));
                $this->assertNotNull($payment = Payments::findOne($data['id']));
                $this->assertEquals($data['amount'], $payment->amount);
                $this->assertEquals($data['is_first'], $payment->is_first);
                $this->assertNotNull($payment->changed);

                break;

            case 3: // noting to update #2
                $this->assertNotNull($payment = Payments::findOne($data['id']));
                $this->assertCount(0, $this->service->updatePayment($data));
                $this->assertNotNull($payment2 = Payments::findOne($data['id']));
                $this->assertEquals($payment->getAttributes(), $payment2->getAttributes());

                break;

            case 4: // успешное создание платежа со стаусом 0

                $this->assertNull($payment = Payments::findOne($data['id']));

                 $result =  $this->service->updatePayment($data);

                $this->assertNotNull($payment = Payments::findOne($data['id']));

                $this->assertCount(7, $result);
                $this->assertEquals($data['amount'], $payment->amount);
                $this->assertEquals($data['customer_id'], $payment->customer_id);
                $this->assertEquals($data['billing'], $payment->billing);
                $this->assertEquals($data['status'], $payment->status);
                $this->assertEquals($data['currency'], $payment->currency);

                break;

            case 8: // если статус не задан, и нет платежа, то апдейт возвращает null и платёж не появляется
                $this->assertNull(Payments::findOne($data['id']));
                $this->assertNull($this->service->updatePayment($data));
                $this->assertNull(Payments::findOne($data['id']));

                break;

            case 6: // id is required, except exception
                $this->expectException(InvalidArgumentException::class);
                $this->service->updatePayment($data);

                break;

            case 7: // update with convert attributes
                $this->assertNotNull($payment = Payments::findOne($data['id']));
                
                $data = $this->service->convertAttributes($data);
                $this->assertCount(3, $this->service->updatePayment($data));

                break;

            case 9: // успешное создание платежа со стаусом 2

                $this->assertNull($payment = Payments::findOne($data['id']));

                $result =  $this->service->updatePayment($data);

                $this->assertNotNull($payment = Payments::findOne($data['id']));

                $this->assertCount(5, $result);
                $this->assertEquals($data['amount'], $payment->amount);
                $this->assertEquals($data['customer_id'], $payment->customer_id);
                $this->assertEquals($data['status'], $payment->status);

                break;

            case 10: // успешное создание платежа со стаусом 3

                $this->assertNull($payment = Payments::findOne($data['id']));

                $result =  $this->service->updatePayment($data);

                $this->assertNotNull($payment = Payments::findOne($data['id']));

                $this->assertCount(5, $result);
                $this->assertEquals($data['amount'], $payment->amount);
                $this->assertEquals($data['customer_id'], $payment->customer_id);
                $this->assertEquals($data['status'], $payment->status);

                break;
        }
    }

    /**
     * @dataProvider attributesDataProvider
     * @param array $data
     */
    public function testConvertAttributes($data)
    {
        $result = $this->service->convertAttributes($data);

        $this->assertCount(4, $result);

        $this->assertTrue(array_key_exists('id', $result));
        $this->assertTrue(array_key_exists('created', $result));
        $this->assertTrue(array_key_exists('customer_id', $result));
        $this->assertTrue(array_key_exists('billing', $result));

        $this->assertFalse(array_key_exists('date_time', $result));
        $this->assertFalse(array_key_exists('user_id', $result));
    }

    /**
     * Sync by customer id
     */
    public function testSyncByCustomer()
    {
        $customerId = 102;
        $this->assertEquals(1, Payments::find()->where(['customer_id' => $customerId])->count());
        $this->assertEquals(4, $this->service->syncPaymentsByCustomer($customerId));
        $this->assertEquals(4, Payments::find()->where(['customer_id' => $customerId])->count());
    }

    /**
     * @return array
     */
    public function attributesDataProvider(): array
    {
        return [
            [
                [
                    'id'        => 555155,
                    'date_time' => 1492086044,
                    'user_id'   => 228054,
                    'billing'   => 'sterling',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function updateDataProvider(): array
    {
        return [
            [
                0,
                [
                    'id'          => 555155,
                    'created'     => 1492086044,
                    'customer_id' => 228054,
                    'billing'     => 'sterling',
                    'status'      => 1,
                    'amount'      => 50050,
                    'paid_date'   => 1476143510,
                    'currency'    => 'UNKNOWN',
                ],
            ],
            [
                1,
                [
                    'id'          => 555155,
                    'created'     => 1492086044,
                    'customer_id' => 228054,
                    'billing'     => 'sterling',
                    'status'      => 1,
                    'amount'      => 50050,
                    'paid_date'   => 1476143510,
                    'currency'    => 'VND',
                ],
            ],
            [
                2,
                [
                    'id'       => 2,
                    'amount'   => 50040.00,
                    'is_first' => 1,
                ],
            ],
            [
                3,
                [
                    'id'          => 2,
                    'created'     => 1524065373,
                    'customer_id' => 102,
                    'billing'     => 'ecomm',
                    'paid_date'   => 1524065556,
                    'status'      => 0,
                    'currency'    => 'THB',
                    'amount'      => 35.00,
                    'is_first'    => 0,
                ],
            ],
            [
                4,
                [
                    'id'          => 555156,
                    'created'     => 1492084101,
                    'customer_id' => 228054,
                    'billing'     => 'sterling',
                    'status'      => 0,
                    'amount'      => 30400.00,
                    'currency'    => 'VND',
                ],
            ],
            [
                5,
                [
                    'id'          => 555156,
                    'created'     => 1492084101,
                    'customer_id' => 228054,
                    'billing'     => 'offline_wire_transfer',
                    'status'      => 1,
                    'amount'      => 30400,
                    'paid_date'   => 1476143121,
                    'currency'    => 'PHP',
                ],
            ],

            [
                6,
                [
                    'created'     => 1492084141,
                    'status'      => 0,
                ],
            ],
            [
                7,
                [
                    'id'        => 2,
                    'date_time' => 1492084141,
                    'user_id'   => 228055,
                    'status'    => 1,
                ],
            ],
            [
                8,
                [
                    'id'        => 555157,
                    'date_time' => 1492084191,
                    'user_id'   => 228056,
                ],
            ],
            [
                9,
                [
                    'id'          => 555156,
                    'created'     => 1492084101,
                    'customer_id' => 228054,
                    'status'      => 2,
                    'amount'      => 200.00,
                ],
            ],
            [
                10,
                [
                    'id'          => 555156,
                    'created'     => 1492084101,
                    'customer_id' => 228054,
                    'status'      => 3,
                    'amount'      => 200.00,
                ],
            ],
        ];
    }
}
