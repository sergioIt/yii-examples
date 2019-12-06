<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 15.05.2018, 15:50
 */

namespace tests\fixtures;

use app\models\AffectedCustomers;
use yii\test\ActiveFixture;

/**
 * Class AffectedCustomersFixture
 * @package tests\_fixtures
 */
class AffectedCustomersFixture extends ActiveFixture
{
    public $modelClass = AffectedCustomers::class;
    public $dataFile = __DIR__ . '/data/affected_customers.php';
}
