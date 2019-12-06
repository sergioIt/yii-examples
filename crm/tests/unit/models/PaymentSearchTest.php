<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 20.08.19
 * Time: 16:58
 */

namespace app\tests\unit\models;


use app\models\PaymentSearch;
use app\models\trade\PaymentChanges;
use app\models\trade\PromoCode;
use app\tests\fixtures\PaymentChangesFixture;
use app\tests\fixtures\PaymentFixture;
use tests\fixtures\PromoCodeFixture;
use tests\unit\BaseUnit;

class PaymentSearchTest extends BaseUnit
{
    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'payment' => PaymentFixture::class,
            'payment_changes' => PaymentChangesFixture::class,
            'promo_code' => PromoCodeFixture::class,
        ];
    }

    public function testSearchForCustomer(){

        $customerId = 102;

        $searchModel = new PaymentSearch();

        $searchModel->user_id =$customerId;
        $dataProvider = $searchModel->search([]);

        $this->assertEquals(1, $dataProvider->getCount());

        $models = $dataProvider->getModels();

        $this->assertArrayHasKey(0, $models);

        $model = $models[0];

        $this->assertArrayHasKey('id', $model);
        $this->assertArrayHasKey('user_id', $model);
        $this->assertArrayHasKey('billing', $model);
        $this->assertArrayHasKey('status', $model);
        $this->assertArrayHasKey('lastApproveStatusChange', $model);
        $this->assertArrayHasKey('promoCode', $model);

        $this->assertInstanceOf(PaymentChanges::class, $model['lastApproveStatusChange']);
        $this->assertInstanceOf(PromoCode::class, $model['promoCode']);

        $this->assertEquals(22, $model['id']);
        $this->assertEquals('offline_wire_transfer', $model['billing']);
        $this->assertEquals($customerId, $model['user_id']);

        $this->assertEquals(22, $model['lastApproveStatusChange']['payment_id']);
        $this->assertEquals('status', $model['lastApproveStatusChange']['change_type']);

        $this->assertEquals(100, $model['promoCode']['id']);
        $this->assertEquals('GBPU7ZF', $model['promoCode']['code']);

    }
}
