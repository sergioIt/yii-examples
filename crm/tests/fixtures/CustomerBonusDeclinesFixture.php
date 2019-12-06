<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 18.06.19
 * Time: 14:52
 */

namespace app\tests\fixtures;


use yii\test\ActiveFixture;

/**
 * Class CustomerBonusDeclinesFixture
 * @package app\tests\fixtures
 */
class CustomerBonusDeclinesFixture extends ActiveFixture
{
    public $db = 'db2';
    public $modelClass = 'app\models\CustomerBonusDeclines';
    public $dataFile = '@tests/fixtures/data/customer_bonus_declines.php';
}
