<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 10.06.19
 * Time: 12:55
 */

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class CustomerStatusChangesFixture extends ActiveFixture
{
    public $db = 'db2';
    public $modelClass = 'app\models\CustomerStatusChanges';
    public $dataFile =  __DIR__.'/data/customer_status_changes.php';
}
