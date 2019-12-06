<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 19.08.19
 * Time: 17:37
 */

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class TournamentOperationFixture
 * @package app\tests\fixtures
 */
class TournamentOperationFixture extends ActiveFixture
{
    public $db = 'db2';
    public $modelClass = 'app\models\trade\TournamentOperations';
    public $dataFile = '@tests/fixtures/data/tournament_operations.php';
}
