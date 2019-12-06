<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 18.06.19
 * Time: 16:56
 */

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class CallsFixture
 * @package app\tests\fixtures
 */
class CallsFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Calls';
    public $dataFile = '@tests/fixtures/data/calls.php';
}
