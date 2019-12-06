<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 06.09.2018, 16:33
 */

namespace tests\models;

use app\models\Country;
use tests\fixtures\CountryFixture;
use tests\unit\BaseUnit;

/**
 * Class CountryTest
 * @package tests\models
 */
class CountryTest extends BaseUnit
{
    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'countries' => CountryFixture::class,
        ];
    }

    public function testGetCountry()
    {
        $this->assertEquals('vn', Country::getLanguageByCode('VNM'));
        $this->assertEquals('ph', Country::getLanguageByCode('PHL'));
        $this->assertEquals('en', Country::getLanguageByCode('NO-NAME'));
    }
}