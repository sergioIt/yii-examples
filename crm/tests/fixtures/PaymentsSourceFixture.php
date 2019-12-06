<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 16.05.2018, 15:43
 */

namespace tests\fixtures;

use app\models\Payment;
use yii\test\ActiveFixture;

/**
 * Class PaymentsSourceFixture
 * @package tests\fixtures
 */
class PaymentsSourceFixture extends ActiveFixture
{
    public $db = 'db2';
    public $modelClass = Payment::class;
    public $dataFile = __DIR__ . '/data/payments_source.php';
}
