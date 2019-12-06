<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 27.06.19
 * Time: 13:51
 */

namespace app\tests\unit\models;


use app\models\Withdraw;
use app\models\WithdrawSearch;
use app\tests\fixtures\WithdrawFixture;
use tests\unit\BaseUnit;

/**
 * Class WithdrawSearch
 * @package app\tests\unit\models
 */
class WithdrawSearchTest extends BaseUnit
{

    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'withdraw' => WithdrawFixture::class,
        ];
    }

    public function testSearch(){

        $searchModel = new WithdrawSearch();
        $searchModel->user_id = 106;
        $dataProvider = $searchModel->search([]);

        $models = $dataProvider->getModels();

        $this->assertArrayHasKey(0, $models);
        $withdraw = $models[0];
        $this->assertInstanceOf(Withdraw::class, $withdraw);

        $this->assertEquals(1, $withdraw->id);
        $this->assertEquals(106, $withdraw->user_id);
    }
}
