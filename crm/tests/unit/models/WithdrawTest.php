<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 27.06.19
 * Time: 14:00
 */

namespace app\tests\unit\models;


use app\models\Withdraw;
use app\tests\fixtures\WithdrawFixture;
use tests\unit\BaseUnit;

/**
 * Class WithdrawTest
 * @package app\tests\unit\models
 */
class WithdrawTest extends BaseUnit
{
    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'withdraw' => WithdrawFixture::class,
        ];
    }

    /**
     *
     */
    public function testGetFilterStatusMap(){

        $withdraw = Withdraw::findOne(1);

        $this->assertInstanceOf(Withdraw::class, $withdraw);

        $this->assertEquals([0,4,6], $withdraw->getFilterStatusMap(0));
        $this->assertEquals([1], $withdraw->getFilterStatusMap(1));
        $this->assertEquals([2,5], $withdraw->getFilterStatusMap(2));
        $this->assertEquals([3], $withdraw->getFilterStatusMap(3));
        $this->assertEquals([], $withdraw->getFilterStatusMap(44));

    }

}
