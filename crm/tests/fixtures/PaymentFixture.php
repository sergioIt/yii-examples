<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 10.06.19
 * Time: 17:30
 */

namespace app\tests\fixtures;

use app\models\Payment;
use yii\test\ActiveFixture;

class PaymentFixture extends ActiveFixture
{
    public $db = 'db2';
    public $modelClass = Payment::class;
    public $dataFile =  __DIR__ . '/data/payment.php';

}
