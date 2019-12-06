<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 19.04.19
 * Time: 14:33
 */

namespace app\tests\fixtures;


use yii\test\ActiveFixture;

class TournamentInvitationLogFixture extends ActiveFixture
{
    public $modelClass = 'app\models\crm\TournamentInvitationLog';
    public $dataFile = '@tests/fixtures/data/tournament_invitation_log.php';
}
