<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 29.05.19
 * Time: 16:20
 */

namespace app\tests\acceptance_base\AdminRole;


class DashBoardCest
{
    public function listIndex(\Acceptance_baseTester $I){

        $I->loggedAs('admin');

        $I->amOnPage('/');

        $I->see('Admin Dashboard');
        $I->see('Call engine connection state');
    }

}
