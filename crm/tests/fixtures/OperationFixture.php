<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 23.08.19
 * Time: 16:25
 */

namespace tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class OperationFixture
 * @package tests\fixtures
 */
class OperationFixture extends ActiveFixture
{
    public $db = 'db2';
    public $modelClass = 'app\models\Operation';
    public $dataFile = __DIR__.'/data/operations.php';
}
