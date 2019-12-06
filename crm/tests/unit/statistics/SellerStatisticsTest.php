<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 15.04.19
 * Time: 16:46
 */

namespace app\tests\unit\statistics;


use app\models\SellerStatistics;
use app\models\Statistics;
use tests\fixtures\CardsFixture;
use tests\fixtures\CustomersFixture;
use tests\unit\BaseUnit;
use yii\db\Query;

/**
 * Class SellerStatisticsTest
 * @package app\tests\unit\statistics
 */
class SellerStatisticsTest extends BaseUnit
{


    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'cards'        => CardsFixture::class,
            'customers'        => CustomersFixture::class,
        ];
    }

    /**
     *
     */
    public function testGetCardsStatusStateQuery(){

        $searchModel = new SellerStatistics(['scenario' => Statistics::SCENARIO_ACTIONS_TOTAL]);

        $query = $searchModel->getCardsStatusStateQuery(['VND']);

        $this->assertInstanceOf(Query::class, $query);

        $data = $query->one();

        $this->assertArrayHasKey('fake_count', $data);
        $this->assertArrayHasKey('progress_count_all', $data);
        $this->assertArrayHasKey('progress_count_not_ready', $data);
        $this->assertArrayHasKey('progress_count_ready', $data);
        $this->assertArrayHasKey('progress_recall_empty', $data);
        $this->assertArrayHasKey('not_on_phone_count', $data);
        $this->assertArrayHasKey('decline_count', $data);
        $this->assertArrayHasKey('approve_count', $data);
        $this->assertArrayHasKey('total_count', $data);

        $this->assertTrue($data['progress_count_all'] >= 2);
        $this->assertTrue($data['total_count'] >= 3);
    }
}
