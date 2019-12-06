<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 10.06.19
 * Time: 17:22
 */

namespace app\tests\fixtures;


use yii\test\ActiveFixture;

/**
 * Class PaymentChangesFixture
 * @package app\tests\fixtures
 */
class PaymentChangesFixture extends ActiveFixture
{
    public $db = 'db2';
    public $modelClass = 'app\models\trade\PaymentChanges';
    public $dataFile =  __DIR__.'/data/payment_changes.php';
}
