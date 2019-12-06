<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 24.05.2018, 12:59
 */

namespace tests\fixtures;

use app\models\LogTransitsManager;
use yii\test\ActiveFixture;

/**
 * Class LogTransitsManagerFixture
 * @package tests\fixtures
 */
class LogTransitsManagerFixture extends ActiveFixture
{
    public $modelClass = LogTransitsManager::class;
    public $dataFile = __DIR__ . '/data/log_transits_manager.php';
}
