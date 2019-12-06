<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 19.04.19
 * Time: 17:05
 */

namespace app\tests\unit\models;


use app\models\crm\TournamentInvitationLog;
use app\tests\fixtures\TournamentInvitationLogFixture;
use tests\unit\BaseUnit;

/**
 * Class TournamentInvitationLogTest
 * @package app\tests\unit\models
 */
class TournamentInvitationLogTest extends BaseUnit
{

    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'log' => TournamentInvitationLogFixture::class,
        ];
    }


    /**
     * тест на создание новой записи в статусе pending
     */
    public function testInitPending(){

        $log = TournamentInvitationLog::initPending(2, 108);

        $this->assertNotNull($log);

        $this->assertEquals('pending', $log->status);
        $this->assertEquals(2, $log->user_id);
        $this->assertEquals(108, $log->customer_id);
    }
}
