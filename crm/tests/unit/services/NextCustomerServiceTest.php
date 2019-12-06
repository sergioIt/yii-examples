<?php
/**
 * Created by PhpStorm.
 * User: makryun
 * Date: 19/03/2019
 * Time: 15:02
 */

namespace tests\services;


use app\exceptions\NextCustomerServiceException;
use app\models\Customers;
use app\services\NextCustomerService;
use app\tests\fixtures\QueueFixture;
use app\tests\fixtures\QueueUsersFixture;
use tests\fixtures\CardsFixture;
use tests\fixtures\CountryFixture;
use tests\fixtures\CustomersFixture;
use tests\unit\BaseUnit;
use yii\db\Query;

/**
 * Class NextCustomerServiceTest
 * @package tests\services
 */
class NextCustomerServiceTest extends BaseUnit
{
    /**
     * @var NextCustomerService
     */
    protected $service;

    protected function setUp()
    {
        parent::setUp();
    }

    public function fixtures(): array
    {
        return [
            'queue' => QueueFixture::class,
            'queue_users' => QueueUsersFixture::class,
            'customers' => CustomersFixture::class,
            'countries' => CountryFixture::class,
            'cards' => CardsFixture::class,
        ];
    }


    /**
     * проверяет, что запрос на
     */
    public function testGetEnabledQueueQueryForUser(){

        $userId = 2;

        $service = new NextCustomerService($userId);

        $query = $service->getEnabledQueueQueryForUser($userId);

        $this->assertInstanceOf(Query::class, $query);

        $data =  $query->select(['qu.queue_id'])->column();

        $this->assertTrue(is_array($data));

        $this->assertEquals(5, count($data));


    }

    public function testGetEnabledQueuesForUser(){

        $userId= 2;

        $service = new NextCustomerService($userId);

        $queues = $service->getEnabledQueuesForUser($userId);

        $this->assertTrue(is_array($queues));

        $this->assertEquals(5, count($queues));
    }


}
