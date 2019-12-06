<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25.03.19
 * Time: 18:42
 */

namespace app\tests\fixtures;
use yii\test\ActiveFixture;


/**
 * Class QueueFixture
 * @package app\tests\fixtures
 */
class QueueFixture  extends ActiveFixture
{
    public $modelClass = 'app\models\crm\Queue';
    public $dataFile = __DIR__ . '/data/queue.php';
}
