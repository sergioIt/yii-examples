<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 10.07.19
 * Time: 18:21
 */

namespace tests\fixtures;

use yii\test\ActiveFixture;

class CustomerActionsFixture extends ActiveFixture
{
    public $modelClass = 'app\models\CustomerActions';
    public $dataFile = '@tests/fixtures/data/customer_actions.php';
}
