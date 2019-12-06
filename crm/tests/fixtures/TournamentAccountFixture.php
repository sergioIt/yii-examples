<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 15.05.19
 * Time: 13:39
 */

namespace tests\fixtures;

use yii\test\ActiveFixture;

class TournamentAccountFixture extends ActiveFixture
{
    public $modelClass = 'app\models\crm\TournamentAccount';
    public $dataFile = __DIR__ . '/data/tournament_account.php';
}
