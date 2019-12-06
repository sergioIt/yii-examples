<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 29.05.19
 * Time: 16:27
 */

namespace app\tests\acceptance_base\SuperAdminRole;


class DashBoardCest
{
    public function listIndex(\Acceptance_baseTester $I){

        $I->loggedAs('super-admin');

        $I->amOnPage('/');

        $I->see('Admin Dashboard');
        $I->see('Call engine connection state');
        $I->see('Call engine queue state');
    }

}
