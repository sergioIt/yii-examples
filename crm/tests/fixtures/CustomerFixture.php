<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 21.05.2018, 16:07
 */

namespace tests\fixtures;

use app\models\Customer;
use yii\test\ActiveFixture;

/**
 * Class CustomerFixture
 * @package tests\fixtures
 */
class CustomerFixture extends ActiveFixture
{
    public $db = 'db2';
    public $modelClass = Customer::class;
    public $dataFile = __DIR__ . '/data/customers.php';
}
