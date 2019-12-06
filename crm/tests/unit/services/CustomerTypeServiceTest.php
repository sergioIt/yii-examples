<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 22.03.19
 * Time: 13:54
 */

namespace app\tests\unit\services;


use app\models\Customers;
use app\models\Payments;
use app\services\CustomerTypeService;
use app\tests\fixtures\CustomerDataFixture;
use tests\fixtures\CardsFixture;
use tests\fixtures\CustomersFixture;
use tests\fixtures\InitialDepositsFixture;
use tests\fixtures\PaymentsFixture;
use tests\fixtures\TournamentAccountFixture;
use tests\fixtures\TournamentFixture;
use tests\unit\BaseUnit;

/**
 * Class CustomerTypeServiceTest
 * @package app\tests\unit\services
 */
class CustomerTypeServiceTest extends BaseUnit
{
    /**
     * @var CustomerTypeService
     */
    protected $service;

    /* @inheritdoc */
    protected function setUp()
    {
        parent::setUp();
    }

    public function fixtures(): array
    {
        return [
            'customers' => CustomersFixture::class,
            'payment' => PaymentsFixture::class,
            'cards' => CardsFixture::class,
            'payments' => PaymentsFixture::class,
            'init_deposits' => InitialDepositsFixture::class,
            'customers_data' => CustomerDataFixture::class,
            'tournaments' => TournamentFixture::class,
            'tournaments_accounts' => TournamentAccountFixture::class,
        ];
    }


    /**
     *
     */
    public function testGetTypeData(){

        $service = new CustomerTypeService(766553);

        $data = $service->getTypeData();

        $this->assertTrue(is_array($data));

        $this->assertArrayHasKey('age', $data);
        $this->assertArrayHasKey('type', $data);
        $this->assertArrayHasKey('type_string', $data);
        $this->assertArrayHasKey('smart_types', $data);

    }

    /**
     * тестирует определение различных типов клиентов
     */
    public function testDefineExpectedTypes(){

        // определение demo active
        $service = new CustomerTypeService(766554);

        $data = $service->getTypeData();

        $this->assertEquals(2, $data['type']);
        $this->assertEquals('demo_active', $data['type_string']);
        $this->assertNull($data['age']);

        // определение real asleep
        $service = new CustomerTypeService(766555);

        $data = $service->getTypeData();

        $this->assertEquals(3, $data['type']);
        $this->assertEquals('real_stopped', $data['type_string']);
        $this->assertNull($data['age']);
    }

    public function testGetSmartTypes(){

        $service = new CustomerTypeService(101);

        $this->assertTrue(is_array($service->getSmartTypes()));
    }

    /**
     * @dataProvider  getTestTypesData
     *  @param $i
     * @param $data
     * тест на попадание типа demo_no_activity_1day в набор smartTypes
     */
    public function testDefineCertainType(int $i, array $data){

        switch ($i){

            case 0: // ожидаем, что определится тип demo_no_activity_1day

                $customer = Customers::findOne($data['customer_id']);

                $customer->reg_date = time() - 60 * 60 * 36;
//
        $this->assertTrue($customer->save());

        $service = new CustomerTypeService($customer->id);

        $this->assertTrue(in_array($data['type'], $service->getSmartTypes()));

                break;

            case 1: // ожидаем, что определится тип demo_no_activity_3hours


                $customer = Customers::findOne($data['customer_id']);
                $customer->reg_date = time() - 60 * 60 * 3;
                $this->assertTrue($customer->save());

                $service = new CustomerTypeService($customer->id);

                $this->assertTrue(in_array($data['type'], $service->getSmartTypes()));

                break;

            case 2: // ожидаем, что определится тип demo_no_fillup, demo_no_tournaments

                $customer = Customers::findOne($data['customer_id']);
                $customer->reg_date = time() - 60 * 60 * 36;
                $this->assertTrue($customer->save());

                $service = new CustomerTypeService($customer->id);

                $types =  $service->getSmartTypes();
                $this->assertTrue(in_array($data['type'], $types));
                $this->assertTrue(in_array('demo_no_tournaments', $types));

                break;

            case 3: // ожидаем, что определится тип all_no_pay_tournaments

                $customer = Customers::findOne($data['customer_id']);
                $customer->reg_date = time() - 60 * 60 * 48;
                $this->assertTrue($customer->save());

                $service = new CustomerTypeService($customer->id);

                $this->assertTrue(in_array($data['type'], $service->getSmartTypes()));

                break;

            case 4: // ожидаем, что определится тип all_no_pay_tournaments


                $customer = Customers::findOne($data['customer_id']);
                $customer->reg_date = time() - 60 * 60 * 4;
                $this->assertTrue($customer->save());

                $service = new CustomerTypeService($customer->id);

                $this->assertTrue(in_array($data['type'], $service->getSmartTypes()));

                break;

            case 5: // ожидаем, что определится тип demo_recent_payment_failed

                $customer = Customers::findOne($data['customer_id']);

                $payment = Payments::findOne($data['payment_id']);

                $payment->paid_date = time() - 60 * 60 * 4;

                $this->assertTrue($payment->save());

                $service = new CustomerTypeService($customer->id);

                $this->assertTrue(in_array($data['type'], $service->getSmartTypes()));

                break;

            case 6: //  ожидаем, что НЕ определится тип demo_recent_payment_failed, потому что последний платёж в статусе pending

                $customer = Customers::findOne($data['customer_id']);

                $lastPendingPayment = Payments::findOne($data['payment_id']);

                $lastPendingPayment->paid_date = time() - 60 * 60 * 4;

                $this->assertTrue($lastPendingPayment->save());

                $service = new CustomerTypeService($customer->id);

                $this->assertFalse(in_array($data['type'], $service->getSmartTypes()));
        }

    }

    /**
     * @return array
     */
    public function getTestTypesData():array {

        return [

            [
                0,
                [
                    'customer_id' => 117,
                    'type' => 'demo_no_activity_1day',
                ],
            ],
            [
                1,
                [
                    'customer_id' => 117,
                    'type' => 'demo_no_activity_3hours',
                ],
            ],
            [
                2,
                [
                    'customer_id' => 118,
                    'type' => 'demo_no_fillup',
                ],
            ],
            [
                3,
                [
                    'customer_id' => 123,
                    'type' => 'all_no_pay_tournaments',
                ],
            ],
            [
                4,
                [
                    'customer_id' => 121,
                    'type' => 'demo_no_refill',
                ],
            ],
            [
                5,
                [
                    'customer_id' => 126,
                    'type' => 'demo_recent_payment_failed',
                    'payment_id' => 16,
                ],
            ],
            [
                6,
                [
                    'customer_id' => 127,
                    'type' => 'demo_recent_payment_failed',
                    'payment_id' => 17,
                ],
            ],

            ];

    }
}
