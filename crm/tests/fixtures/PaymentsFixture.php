<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 15.05.2018, 16:27
 */

namespace tests\fixtures;

use app\models\Payments;
use yii\test\ActiveFixture;

/**
 * Class PaymentsFixture
 * @package tests\fixtures
 */
class PaymentsFixture extends ActiveFixture
{
    public $modelClass = Payments::class;
    public $dataFile = __DIR__ . '/data/payments.php';
}
