<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 20.06.19
 * Time: 18:39
 */

namespace app\tests\acceptance_base\SupportRole;


class CallCest
{
    /**
     * @param \Acceptance_baseTester $I
     *  @todo чтобы этоот тест сработал, нужно создат базу asterisk_db в tests_db.sh
     */
    public function listenerSupport(\Acceptance_baseTester $I){

//        $I->loggedAs('support');
//
//        $I->amOnPage('/call/listener-support');
//
//        $I->see('Incoming call listener');
    }
}
