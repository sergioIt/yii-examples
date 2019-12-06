<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 18.06.19
 * Time: 14:42
 */

namespace app\tests\fixtures;


use yii\test\ActiveFixture;

/**
 * Class WithdrawFixture
 * @package app\tests\fixtures
 */
class WithdrawFixture extends ActiveFixture
{
    public $db = 'db2';
    public $modelClass = 'app\models\Withdraw';
    public $dataFile = '@tests/fixtures/data/withdraw.php';
}
