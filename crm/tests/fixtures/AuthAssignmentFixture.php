<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 23.08.19
 * Time: 17:24
 */

namespace tests\fixtures;

use yii\test\ActiveFixture;

class AuthAssignmentFixture extends ActiveFixture
{
    public $modelClass = 'app\models\AuthAssignment';
    public $dataFile =  __DIR__.'/data/auth_assignment.php';
}
