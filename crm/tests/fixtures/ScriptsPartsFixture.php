<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 31.07.2018, 14:34
 */

namespace tests\fixtures;

use app\models\crm\ScriptsParts;
use yii\test\ActiveFixture;

/**
 * Class ScriptsPartsFixture
 * @package tests\unit\fixtures
 */
class ScriptsPartsFixture extends ActiveFixture
{
    public $modelClass = ScriptsParts::class;
    public $dataFile = __DIR__ . '/data/scripts_parts.php';
}