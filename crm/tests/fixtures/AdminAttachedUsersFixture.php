<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 29.05.19
 * Time: 16:48
 */

namespace app\tests\fixtures;


use yii\test\ActiveFixture;

class AdminAttachedUsersFixture extends ActiveFixture
{
    public $modelClass = 'app\models\crm\AdminAttachedUsers';
    public $dataFile = __DIR__ . '/data/admin_attached_users.php';
}
