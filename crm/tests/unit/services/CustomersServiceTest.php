<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 26.09.18
 * Time: 18:52
 */

namespace tests\services;

use app\exceptions\CustomersServiceException;
use app\models\Customers;
use app\services\CustomersService;
use tests\fixtures\CustomersFixture;
use tests\unit\BaseUnit;

/**
 * Class CustomersServiceTest
 * @package tests\services
 */
class CustomersServiceTest extends BaseUnit
{
    /**
     * @var CustomersService
     */
    protected $service;

    /* @inheritdoc */
    protected function setUp()
    {
        parent::setUp();
        $this->service = new CustomersService();
    }

    //$itemJson =  '{"user_id":768545,"card_id":false,"fields":{"status":0,"currency":"INR","reg_date":1537957210,"reg_device":"web_desktop","reg_ip":"192.168.89.232"}}';

    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'customers' => CustomersFixture::class,
        ];
    }

    /**
     * @dataProvider updateDataProvider()
     * @param int $i
     * @param array $data
     * @return bool
     */
    public function testUpdateMainData(int $i, array $data)
    {

        switch ($i) {


            case 0: // апдейт существующего поля у сущетвующего клиента

                $this->assertNotNull($customer = Customers::findOne($data['id']));

                $this->assertEquals(1, $this->service->updateMainById($data['id'], $data['data']));

                $customer->refresh();

                $this->assertEquals($data['data']['phone'], $customer->phone);


                break;

            case 1: // апдейт  несуществующего поля у сущетвующего клиента

                $this->assertNotNull($customer = Customers::findOne($data['id']));

                $this->assertEquals(0, $this->service->updateMainById($data['id'], $data['data']));

                break;

            case 2: // успешное создание новой записи (только по reg_date, даже без валюты)

                $this->assertNull($customer = Customers::findOne($data['id']));

                $this->assertEquals(1, $this->service->updateMainById($data['id'], $data['data']));

                $this->assertNotNull($customer = Customers::findOne($data['id']));

                $this->assertEquals($data['id'], $customer->id);
                $this->assertEquals($data['data']['reg_date'], $customer->reg_date);

                break;

            case 3: // искючение: нет данных по reg_date при попытке создания новой записи

                $this->assertNull($customer = Customers::findOne($data['id']));

                $this->expectException(CustomersServiceException::class);
                $this->service->updateMainById($data['id'], $data['data']);

                break;
        }

        return true;
    }

    /**
     * @dataProvider updateOperationsDataProvider()
     * @param int $i
     * @param array $data
     */
    public function testUpdateOperations(int $i, array $data)
    {

        switch ($i) {


            case 0: // успешный апдейт op_count_demo, last_operation_demo существующего клиента, с непустым op_count_demo

                $this->assertNotNull($customer = Customers::findOne($data['id']));

                $this->assertNotNull($opCount = $customer->op_count_demo);


                $mapped = $this->service->mapOperationsData($data['data']);

              $this->assertEquals(1, $this->service->updateOperations($data['id'], $mapped) );


                    $customer->refresh();

                    $this->assertEquals($data['data']['open_time'], $customer->last_operation_demo);
                    $this->assertEquals($opCount+1, $customer->op_count_demo);

                break;

            case 1: // успешный апдейт op_count_demo, last_operation_demo существующего клиента, с пустым op_count_demo

                $this->assertNotNull($customer = Customers::findOne($data['id']));

                $this->assertNull($customer->op_count_demo);

                $mapped = $this->service->mapOperationsData($data['data']);

                $this->assertEquals(1, $this->service->updateOperations($data['id'], $mapped) );

                $customer->refresh();

                $this->assertEquals($data['data']['open_time'], $customer->last_operation_demo);
                $this->assertEquals(1, $customer->op_count_demo);

                break;

            case 2: // успешный апдейт last_operation_real существующего клиента

                $this->assertNotNull($customer = Customers::findOne($data['id']));

                $this->assertNull($customer->last_operation_real);

                $mapped = $this->service->mapOperationsData($data['data']);

                $this->assertEquals(1, $this->service->updateOperations($data['id'], $mapped) );

                $customer->refresh();

                $this->assertEquals($data['data']['open_time'], $customer->last_operation_real);

                break;
        }


    }

    public function testGetCustomerByPhoneNumbersQuery()
    {

        $query = $this->service->getCustomerByPhoneNumbersQuery('766455484');

        $data = $query->all();

        $this->assertTrue(is_array($data));
    }

    // @todo чтобы эти тесты заработали в гитлабе, нужно сделать как описано тут https://codeception.com/docs/modules/Redis
//    public function testPutOnlineUsersToTop()
//    {
//
//        $users = [121, 122];
//
//        $usersSorted = CustomersService::putOnlineUsersToTop([121, 122]);
//
//        $this->assertEquals($users, $usersSorted);
//    }

//    public function testGetOnlineCustomers()
//    {
//
//        $data = $this->service->getOnlineCustomers();
//
//        $this->assertEquals([], $data);
//    }

    public function testPhoneIsValid()
    {

        // VND - 11 цифр
        $this->assertTrue($this->service->phoneIsValid('84445454455', 'VND'));
        $this->assertTrue($this->service->phoneIsValid('+84445454455', 'VND'));

        // THB - 11 цифр
        $this->assertTrue($this->service->phoneIsValid('+66445454455', 'THB'));
        $this->assertTrue($this->service->phoneIsValid('66445454455', 'THB'));

        // PHP -12 цифр
        $this->assertTrue($this->service->phoneIsValid('+634454544550', 'PHP'));
        $this->assertTrue($this->service->phoneIsValid('634454544550', 'PHP'));

        // IDR = 13 цифр
        $this->assertTrue($this->service->phoneIsValid('+6244545445511', 'IDR'));
        $this->assertTrue($this->service->phoneIsValid('6244545445511', 'IDR'));

        // INR - 12 цифр
        $this->assertTrue($this->service->phoneIsValid('+914454545511', 'INR'));
        $this->assertTrue($this->service->phoneIsValid('914454545511', 'INR'));

        // NGN - 13 цифр
        $this->assertTrue($this->service->phoneIsValid('+2344543545511', 'NGN'));
        $this->assertTrue($this->service->phoneIsValid('2344354545511', 'NGN'));

        // проверка что неправльных номеров
        $this->assertFalse($this->service->phoneIsValid('+8444545445', 'VND'));
        $this->assertFalse($this->service->phoneIsValid('+84445454455', 'PHP'));
        $this->assertFalse($this->service->phoneIsValid('+84445454455', 'THB'));
        $this->assertFalse($this->service->phoneIsValid('+66445454455', 'VND'));

        // пустой номер допускается, чтобы можно было проапдейтить phone_valid с true на false если номер стал пустым
        $this->assertFalse($this->service->phoneIsValid('', 'VND'));
        $this->assertFalse($this->service->phoneIsValid(null, 'VND'));

        // тест на несуществующую валюту в конфиге для валюты
        $this->assertFalse($this->service->phoneIsValid('+66445454455', 'AED'));

    }

    /**
     * тест на то, что поле phone_valid апдейтится корректно при сохранеии модели
     */
    public function testUpdatePhoneValid(){

        $this->assertNotNull($customer = Customers::findOne(766553));

        $this->assertNull($customer->phone);

        $this->assertFalse($this->service->phoneIsValid($customer->phone, $customer->currency));
        // задаём правильный номер, и убеждаемся, что после сохранения поле phone_valid становится true

        $customer->phone = '+84642456214';

        $this->assertTrue($customer->save());

        $this->assertTrue($customer->phone_valid);

        $this->assertTrue($this->service->phoneIsValid($customer->phone, $customer->currency));

        // задаём заведомо неправильный номер, и убеждаемся, что после сохранения поле phone_valid становится false
        $customer->phone = '+8464245621';

        $this->assertTrue($customer->save());

        $this->assertFalse($customer->phone_valid);

        $this->assertFalse($this->service->phoneIsValid($customer->phone, $customer->currency));

    }

    /**
     * @return array
     */
    public function updateDataProvider()
    {

        return [

            [
                0,
                [
                    'id' => 108,
                    'data' => [
                        'phone' => '792145454545'
                    ]
                ],
            ],
            [
                1,
                [
                    'id' => 108,
                    'data' => [
                        'not_exists' => '792145454545'
                    ]
                ],
            ],
            [
                2,
                [
                    'id' => 102,
                    'data' => [
                        'reg_date' => 1520348244
                    ]
                ],
            ],
            [
                3,
                [
                    'id' => 102,
                    'data' => [
                        'currency' => 'USD'
                    ]
                ],
            ],


        ];

    }

    /**
     * @return array
     */
    public function updateOperationsDataProvider()
    {

        return [

            [
                0,
                [
                    'id' => 108,
                    'data' => [
                        'real' => 0,
                        'open_time' => 1520348344,
                    ]
                ],
                1,
                [
                    'id' => 109,
                    'data' => [
                        'real' => 0,
                        'open_time' => 1520348344,
                    ]
                ],
                2,
                [
                    'id' => 109,
                    'data' => [
                        'real' => 1,
//                        'open_time' => 1520348344,
                    ]
                ],
            ],
        ];
    }
}
