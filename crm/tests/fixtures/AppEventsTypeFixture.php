<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 16.05.2018, 13:43
 */

namespace tests\fixtures;

use app\models\AppEventsType;
use yii\test\ActiveFixture;

/**
 * Class AppEventsTypeFixture
 * @package tests\fixtures
 */
class AppEventsTypeFixture extends ActiveFixture
{
    public $modelClass = AppEventsType::class;
    public $dataFile = __DIR__ . '/data/app_events_type.php';
}
