<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 26.04.19
 * Time: 16:13
 */

namespace app\tests\unit\models;


use app\models\CardCommonSearch;
use app\models\Customer;
use tests\fixtures\CardsFixture;
use tests\fixtures\CustomersFixture;
use tests\unit\BaseUnit;

/**
 * Class CardCommonSearchTest
 * @package app\tests\unit\models
 */
class CardCommonSearchTest extends BaseUnit
{
    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'cards'           => CardsFixture::class,
            'customers'        => CustomersFixture::class,
        ];
    }

    public function testSearchById(){

       $params['CardCommonSearch'] = [];

       $params['CardCommonSearch']['id'] = 76653;

       $model = new CardCommonSearch();

       $dataProvider = $model->search($params);

        $this->assertEquals(1, $dataProvider->getCount());

        $this->assertTrue(in_array(76653, $dataProvider->getKeys()));
    }


    public function testSearchByEmail(){

       $params['CardCommonSearch'] = [];

       $params['CardCommonSearch']['email'] = 'customer114@irontrade.com';

       $model = new CardCommonSearch();

       $dataProvider = $model->search($params);

        $this->assertEquals(1, $dataProvider->getCount());

        $this->assertTrue(in_array(114, $dataProvider->getKeys()));
    }


    public function testSearchByFullName(){

       $params['CardCommonSearch'] = [];

       $params['CardCommonSearch']['full_name'] = 'sergio';

       $model = new CardCommonSearch();

       $dataProvider = $model->search($params);

        $this->assertEquals(1, $dataProvider->getCount());

        $this->assertTrue(in_array(766553, $dataProvider->getKeys()));
    }

    public function testSearchByPhone(){

       $params['CardCommonSearch'] = [];

       $params['CardCommonSearch']['phone'] = '84998887711';

       $model = new CardCommonSearch();

       $dataProvider = $model->search($params);

        $this->assertTrue(in_array(114, $dataProvider->getKeys()));
        $this->assertTrue(in_array(111, $dataProvider->getKeys()));
        $this->assertTrue(in_array(112, $dataProvider->getKeys()));
    }

    public function testSearchByCurrency(){

       $params['CardCommonSearch'] = [];

       $params['CardCommonSearch']['currency'] = 'INR';

       $model = new CardCommonSearch();

       $dataProvider = $model->search($params);
        // только по валюте искать нельзя (потому что вдаст слишком много результатов)
       $this->assertEquals(0, $dataProvider->getCount());
       $this->assertEquals([], $dataProvider->getModels());
        // добавляем email в параметры запроса
        $params['CardCommonSearch']['email'] = 'customer113';

        $dataProvider = $model->search($params);

        $this->assertEquals(1, $dataProvider->getCount());
        $this->assertTrue(in_array(113, $dataProvider->getKeys()));
    }

   public function testSearchByVerificationStatus(){

       $params['CardCommonSearch'] = [];

       $params['CardCommonSearch']['status'] = Customer::STATUS_REQUESTED_VERIFICATION;

       $model = new CardCommonSearch();

       $dataProvider = $model->search($params);

       // только по статусу искать нельзя (потому что вдаст слишком много результатов)
       $this->assertEquals(0, $dataProvider->getCount());
       $this->assertEquals([], $dataProvider->getModels());

        // добавляем email в параметры запроса
       $params['CardCommonSearch']['email'] = 'customer113';

       $dataProvider = $model->search($params);

       $this->assertEquals(1, $dataProvider->getCount());
       $this->assertTrue(in_array(113, $dataProvider->getKeys()));
    }

    public function testSearchByMode(){

       $params['CardCommonSearch'] = [];

       $params['CardCommonSearch']['real'] = Customer::MODE_REAL;

       $model = new CardCommonSearch();

       $dataProvider = $model->search($params);

       // только по статусу искать нельзя (потому что вдаст слишком много результатов)
       $this->assertEquals(0, $dataProvider->getCount());
       $this->assertEquals([], $dataProvider->getModels());

        // добавляем email в параметры запроса
       $params['CardCommonSearch']['email'] = 'test766555';

       $dataProvider = $model->search($params);

       $this->assertEquals(1, $dataProvider->getCount());
       $this->assertTrue(in_array(766555, $dataProvider->getKeys()));
    }


}
