<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 26.03.19
 * Time: 16:13
 */

namespace app\tests\fixtures;


use yii\test\ActiveFixture;

class QueueUsersFixture extends ActiveFixture
{
    public $modelClass = 'app\models\crm\QueueUsers';
    public $dataFile = __DIR__ . '/data/queue_users.php';
}
