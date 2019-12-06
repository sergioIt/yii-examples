<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 15.05.19
 * Time: 18:35
 */

namespace app\tests\unit\services;


use app\exceptions\CustomersDataServiceException;
use app\models\CustomersData;
use app\services\CustomersDataService;
use app\tests\fixtures\CustomerDataFixture;
use tests\fixtures\CustomersFixture;
use tests\unit\BaseUnit;
use yii\base\InvalidArgumentException;

class CustomerDataServiceTest extends BaseUnit
{

    /**
     * @var CustomersDataService
     */
    protected $service;

    /* @inheritdoc */
    protected function setUp()
    {
        parent::setUp();

        $this->service = new CustomersDataService();
    }

    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'customers'        => CustomersFixture::class,
            'customers_data'        => CustomerDataFixture::class,
        ];
    }

    /**
     *  @dataProvider updateDataProvider
     * @param $i int
     * @param $data
     */
    public function testUpdateAttribute($i, $data){

        switch ($i) {
            // тест на исключение: клиент не найден
            case 0:

              $this->assertFalse($this->service->updateAttribute('last_refill_date',$data));
                break;
            // тест на исключение:  неправильная дата, попытка апдейта 'last_refill_date'
            case 1:
                $this->expectException(InvalidArgumentException::class);
                $this->expectExceptionMessage('Key `date_time` is required and should not be empty');

              $this->service->updateAttribute('last_refill_date',$data);
                break;

            // тест на исключение про неправильный атрибут
            case 2:
                $this->expectException(CustomersDataServiceException::class);
                $this->expectExceptionMessage('not allowed attribute to sync:strange');

              $this->service->updateAttribute('strange',$data);
                break;

            // тест на исключение про отсутсвующий ключ user_id
            case 3:
                $this->expectException(InvalidArgumentException::class);
                $this->expectExceptionMessage('Key `user_id` is required and must be integer');

              $this->service->updateAttribute('last_refill_date', $data);
                break;

            // тест на исключение про отсутсвующий open_time для опреаций
            case 4:
                $this->expectException(InvalidArgumentException::class);
                $this->expectExceptionMessage('Key `open_time` is required and must be integer');

              $this->service->updateAttribute('last_tournament_operation', $data);
                break;

            // тест на успешный апдейт last_fillup_date
            case 5:

              $this->assertTrue($this->service->updateAttribute('last_fillup_date', $data));

                $customerData = CustomersData::findOne(['customer_id' => $data['user_id']]);

                $this->assertEquals($data['date_time'], $customerData->last_fillup_date);

                break;

            // тест на успешный апдейт last_fillup_date
            case 6:

              $this->assertTrue($this->service->updateAttribute('last_refill_date', $data));

              $customerData = CustomersData::findOne(['customer_id' => $data['user_id']]);

                $this->assertEquals($data['date_time'], $customerData->last_refill_date);

                break;

            // тест на успешный апдейт last_tournament_operation

            case 7:

              $this->assertTrue($this->service->updateAttribute('last_tournament_operation', $data));
                $customerData = CustomersData::findOne(['customer_id' => $data['user_id']]);

                $this->assertNotNull($customerData);

                $this->assertEquals($data['open_time'], $customerData->last_tournament_operation);
                break;

            // тест на исключение:  неправильная дата, попытка апдейта 'last_fillup_date'
            case 8:
                $this->expectException(\yii\base\InvalidArgumentException::class);
                $this->expectExceptionMessage('Key `date_time` is required and should not be empty');

                $this->service->updateAttribute('last_fillup_date',$data);
                break;

            // тест на исключение:  неправильная дата, попытка апдейта 'last_tournament_operation'
            case 9:
                $this->expectException(\yii\base\InvalidArgumentException::class);
                $this->expectExceptionMessage('Key `open_time` is required and must be integer');

                $this->service->updateAttribute('last_tournament_operation',$data);
                break;

            // тест на успешный апдейт last_refill_date для уже существующей записи в customers_data
            case 10:

                $customerData = CustomersData::findOne(['customer_id' => $data['user_id']]);

                $this->assertNotNull($customerData);
                $this->assertNotNull($customerData->last_refill_date);

                $this->assertTrue($this->service->updateAttribute('last_refill_date',$data));

                $customerData->refresh();

                $this->assertEquals($data['date_time'], $customerData->last_refill_date);

                break;

            // тест на успешный апдейт last_fillup_date для уже существующей записи в customers_data
            case 11:

                $customerData = CustomersData::findOne(['customer_id' => $data['user_id']]);

                $this->assertNotNull($customerData);
                $this->assertNotNull($customerData->last_fillup_date);

                $this->assertTrue($this->service->updateAttribute('last_fillup_date',$data));

                $customerData->refresh();

                $this->assertEquals($data['date_time'], $customerData->last_fillup_date);

                break;

            // тест на успешный апдейт last_tournament_operation для уже существующей записи в customers_data
            case 12:

                $customerData = CustomersData::findOne(['customer_id' => $data['user_id']]);

                $this->assertNotNull($customerData);
                $this->assertNotNull($customerData->last_tournament_operation);

                $this->assertTrue($this->service->updateAttribute('last_tournament_operation',$data));

                $customerData->refresh();

                $this->assertEquals($data['open_time'], $customerData->last_tournament_operation);

                break;
        }



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
                    'date_time'     => '2019-05-15 12:00:32',
                    'user_id'     => 787296,
                ],
            ],
            [
                1,
                [
                    'id'          => 555155,
                    'date_time'     => false,
                    'user_id'     => 766556,
                ],
            ],
            [
                2,
                [
                    'id'          => 555155,
                    'date_time'     => '2019-05-15 12:00:32',
                    'user_id'     => 766556,
                ],
            ],
            [
                3,
                [
                    'id'          => 555155,
                    'date_time'     => '2019-05-15 12:00:32',
                ],
            ],
            [
                4,
                [
                    'id'          => 555155,
                    'date_time'     => '2019-05-15 12:00:32',
                    'user_id'     => 766556,
                ],
            ],
            [
                5,
                [
                    'id'          => 555155,
                    'date_time'     => '2019-05-15 12:00:32',
                    'user_id'     => 766556,
                ],
            ],
            [
                6,
                [
                    'id'          => 555156,
                    'date_time'     => '2019-05-15 13:00:32',
                    'user_id'     => 766556,
                ],
            ],
            [
                7,
                [
                    'id'          => 2,
                    'open_time'     => 1557937367,
                    'user_id'     => 766553,
                ],
            ],
            [
                8,
                [
                    'id'          => 2,
                    'date_time'     => false,
                    'user_id'     => 766553,
                ],
            ],
            [
                9,
                [
                    'id'          => 2,
                    'open_time'     => '',
                    'user_id'     => 766553,
                ],
            ],
            [
                10,
                [
                    'id'          => 2,
                    'date_time'     => '2019-05-17 13:00:32',
                    'user_id'     => 766555,
                ],
            ],
            [
                11,
                [
                    'id'          => 2,
                    'date_time'     => '2019-05-16 13:00:32',
                    'user_id'     => 766555,
                ],
            ],
            [
                12,
                [
                    'id'          => 2,
                    'open_time'     => 1557937367,
                    'user_id'     => 766555,
                ],
            ],

        ];
    }

}
