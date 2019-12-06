<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 23/05/2019, 18:04
 */

namespace tests\models;

use app\models\InitialDeposits;
use tests\fixtures\InitialDepositsFixture;
use tests\unit\BaseUnit;

/**
 * Class InitialDepositsTest
 * @package tests\models
 */
class InitialDepositsTest extends BaseUnit
{
    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'initialDeposits' => InitialDepositsFixture::class,
        ];
    }

    /**
     * Тест получения начального депозита по валюте
     */
    public function testGetByCurrency()
    {
        $this->assertEquals(1000.00, InitialDeposits::getByCurrency('THB'));
        $this->assertEquals(4000.00, InitialDeposits::getByCurrency('VND'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Initial deposit by NON not found');
        InitialDeposits::getByCurrency('NON');
    }
}
