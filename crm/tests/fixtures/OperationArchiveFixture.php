<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 23.08.19
 * Time: 16:34
 */

namespace tests\fixtures;

use yii\test\ActiveFixture;

class OperationArchiveFixture extends ActiveFixture
{
    public $db = 'db2';
    public $modelClass = 'app\models\trade\OperationArchive';
    public $dataFile = __DIR__.'/data/operations_archive.php';
}
