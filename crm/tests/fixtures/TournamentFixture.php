<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 15.05.19
 * Time: 13:35
 */

namespace tests\fixtures;

use yii\test\ActiveFixture;

class TournamentFixture extends ActiveFixture
{
    public $modelClass = 'app\models\crm\Tournament';
    public $dataFile = __DIR__ . '/data/tournament.php';
}
