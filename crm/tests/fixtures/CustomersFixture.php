<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 26.09.18
 * Time: 20:11
 */

namespace tests\fixtures;

use app\models\Customers;
use yii\test\ActiveFixture;

/**
 * Class CustomersFixture
 * @package app\tests\fixtures
 */
class CustomersFixture extends ActiveFixture
{
    public $modelClass = Customers::class;
    public $dataFile = __DIR__ . '/data/customers_synced.php';
}
