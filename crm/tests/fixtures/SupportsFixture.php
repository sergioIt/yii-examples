<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 21.05.2018, 15:02
 */

namespace tests\fixtures;

use app\models\Support;
use yii\test\ActiveFixture;

/**
 * Class SupportFixture
 * @package tests\fixtures
 */
class SupportsFixture extends ActiveFixture
{
    public $modelClass = Support::class;
    public $dataFile = __DIR__ . '/data/supports.php';
}
