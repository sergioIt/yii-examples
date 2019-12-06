<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 19.08.19
 * Time: 17:35
 */

namespace app\tests\unit\models;


use app\models\Customers;
use app\models\trade\TournamentOperationsSearch;
use app\tests\fixtures\TournamentOperationFixture;
use tests\fixtures\CustomersFixture;
use tests\unit\BaseUnit;

/**
 * Class TournamentOperationsSearchTest
 * @package app\tests\unit\models
 */
class TournamentOperationsSearchTest extends BaseUnit
{
    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'tournament_operations' => TournamentOperationFixture::class,
            'customers' => CustomersFixture::class,
        ];
    }


    public function testExistsForCustomer(){

        $this->assertTrue(TournamentOperationsSearch::existsForCustomer(107));
        $this->assertFalse(TournamentOperationsSearch::existsForCustomer(123));

    }

    /**
     * тест поиска турнирных опреаций для заданного клиента (используется в модалке)
     */
    public function  testSearchForCustomer(){

        $this->assertNotNull($customer  = Customers::findOne(107));

        $searchModel = new TournamentOperationsSearch();
        $searchModel->user_id = $customer->id;

        $dataProvider = $searchModel->search([], $customer->currency);

        $this->assertEquals(1, $dataProvider->getCount());

        $models = $dataProvider->getModels();

        $this->assertArrayHasKey(0, $models);

        $model = $models[0];

        $this->assertArrayHasKey('id', $model);
        $this->assertArrayHasKey('op_interval', $model);
        $this->assertArrayHasKey('open_time', $model);
        $this->assertArrayHasKey('close_time', $model);
        $this->assertArrayHasKey('stock', $model);
        $this->assertArrayHasKey('op_type', $model);
        $this->assertArrayHasKey('diff', $model);
        $this->assertArrayHasKey('amount', $model);
        $this->assertArrayHasKey('profit', $model);

        $this->assertEquals(1, $model['id']);
        $this->assertEquals(30, $model['op_interval']);
        $this->assertEquals(1532360310, $model['open_time']);
        $this->assertEquals(1532360340, $model['close_time']);
        $this->assertEquals('USDRUB', $model['stock']);
        $this->assertEquals(1, $model['op_type']);
        $this->assertEquals('0.00050000', $model['diff']);
        $this->assertEquals('100.0000', $model['amount']);
        $this->assertEquals('20.0000', $model['profit']);

    }

    public function testGetFilterData(){

       $data = TournamentOperationsSearch::getFilterData();

        $this->assertArrayHasKey(0, $data);

        $item = $data[0];

        $this->assertArrayHasKey('op_interval', $item);
        $this->assertArrayHasKey('stock', $item);

        $this->assertEquals(30, $item['op_interval']);
        $this->assertEquals('USDRUB', $item['stock']);

    }
}
