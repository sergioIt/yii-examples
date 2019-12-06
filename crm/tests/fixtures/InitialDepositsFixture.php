<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 23/05/2019, 18:05
 */

namespace tests\fixtures;

use app\models\InitialDeposits;
use yii\test\ActiveFixture;

/**
 * Class InitialDepositsFixture
 * @package tests\fixtures
 */
class InitialDepositsFixture extends ActiveFixture
{
    public $db = 'db';
    public $modelClass = InitialDeposits::class;
    public $dataFile = __DIR__ . '/data/initial-deposits.php';
}
