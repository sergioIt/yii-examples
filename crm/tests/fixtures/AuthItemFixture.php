<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 23.08.19
 * Time: 17:23
 */

namespace tests\fixtures;

use yii\test\ActiveFixture;

class AuthItemFixture extends ActiveFixture
{
    public $modelClass = 'app\models\AuthItem';
    public $dataFile = __DIR__.'/data/auth_item.php';
}
