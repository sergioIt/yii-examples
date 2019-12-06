<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 10.06.19
 * Time: 12:58
 */

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class AdminFixture extends ActiveFixture
{
    public $db = 'db2';
    public $modelClass = 'app\models\Admin';
    public $dataFile = __DIR__.'/data/admin.php';
}
