<?php


namespace app\tests\unit\services;


use app\models\CallsIncomingData;
use app\models\Customers;
use app\services\CallService;
use app\services\CustomersService;
use app\services\UserService;
use tests\fixtures\CallsIncomingDataFixture;
use tests\fixtures\CustomersFixture;
use tests\unit\BaseUnit;

/**
 * Class CallServiceTest
 * @package app\tests\unit\services
 */
class CallServiceTest extends BaseUnit
{
    /**
     * @var UserService
     */
    protected $service;

    /* @inheritdoc */
    protected function setUp()
    {
        parent::setUp();
        $this->service = new CallService(new CustomersService());
    }

    public function fixtures(): array
    {
        return [
            'customers' => CustomersFixture::class,
            'calls_data' => CallsIncomingDataFixture::class,
        ];
    }

    public function testSaveIncoming()
    {

        $this->assertNotNull($customer = Customers::findOne(766552));

        $this->assertTrue($this->service->saveIncomingData('7775543210', 2));

        $this->assertNotNull($data = CallsIncomingData::findOne(1));

        $this->assertEquals($customer->id, $data->customer_id);
        $this->assertEquals(2, $data->type);
        $this->assertNotNull($data->created);

    }
}
