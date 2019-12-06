<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 10.06.19
 * Time: 17:16
 */

namespace app\tests\unit\statistics;


use app\models\trade\PaymentStatusesStatisticSearch;
use app\tests\fixtures\PaymentChangesFixture;
use app\tests\fixtures\PaymentFixture;
use tests\unit\BaseUnit;
use yii\db\ActiveQuery;

/**
 * Class PaymentStatusesStatisticSearch
 * @package app\tests\unit\statistics
 */
class PaymentStatusesStatisticSearchTest extends BaseUnit
{

    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'payment_changes'        => PaymentChangesFixture::class,
            'payment'        => PaymentFixture::class,
        ];
    }

    public function testGetQuery(){

        $searchModel = new PaymentStatusesStatisticSearch();
        $searchModel->groupByAdmin = true;

        $query = $searchModel->getQuery();

        $this->assertInstanceOf(ActiveQuery::class, $query);

        $data = $query->asArray()->indexBy('date')->all();

//        var_dump($data); die();
        $this->assertArrayHasKey('2017-05-22', $data);
        $this->assertArrayHasKey('date', $data['2017-05-22']);
        $this->assertArrayHasKey('total', $data['2017-05-22']);
        $this->assertArrayHasKey('approved', $data['2017-05-22']);
        $this->assertArrayHasKey('declined', $data['2017-05-22']);
        $this->assertArrayHasKey('total_customers', $data['2017-05-22']);
        $this->assertArrayHasKey('approved_customers', $data['2017-05-22']);
        $this->assertArrayHasKey('declined_customers', $data['2017-05-22']);
        $this->assertArrayHasKey('error_customers', $data['2017-05-22']);
        $this->assertArrayHasKey('error', $data['2017-05-22']);
        $this->assertArrayHasKey('admin_id', $data['2017-05-22']);


    }

    public function testGetRawStatQuery(){

        $searchModel = new PaymentStatusesStatisticSearch();
        $query = $searchModel->getRawStatQuery();

        $this->assertInstanceOf(ActiveQuery::class, $query);

        $data = $query->asArray()->indexBy('customer_id')->all();

        $this->assertArrayHasKey(102, $data);
    }


}
