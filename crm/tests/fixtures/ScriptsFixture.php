<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 03.08.18
 * Time: 18:51
 */

namespace app\tests\fixtures;


use app\models\crm\ScriptsParts;
use yii\test\ActiveFixture;

/**
 * Class ScriptsFixture
 * @package app\tests\fixtures
 */
class ScriptsFixture extends ActiveFixture
{
    public $modelClass = ScriptsParts::class;
    public $dataFile = __DIR__ . '/data/scripts.php';
}
