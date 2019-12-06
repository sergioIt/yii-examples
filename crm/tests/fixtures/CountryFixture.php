<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 31.07.2018, 14:34
 */

namespace tests\fixtures;

use app\models\Country;
use yii\test\ActiveFixture;

/**
 * Class ScriptsPartsFixture
 * @package tests\unit\fixtures
 */
class CountryFixture extends ActiveFixture
{
    public $modelClass = Country::class;
    public $dataFile = __DIR__ . '/data/countries.php';
}