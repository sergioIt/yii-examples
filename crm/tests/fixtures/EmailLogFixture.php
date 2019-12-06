<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 29.05.19
 * Time: 18:32
 */

namespace app\tests\fixtures;


use yii\test\ActiveFixture;

class EmailLogFixture extends ActiveFixture
{
    public $modelClass = 'app\models\EmailLog';
    public $dataFile = '@tests/fixtures/data/email_log.php';
}
