<?php


namespace app\tests\acceptance_base\SuperAdminRole;


class SystemCest
{

    public function index(\Acceptance_baseTester $I){

        $I->loggedAs('super-admin');

        $I->amOnPage('/system/auth-log');

        $I->see('Auth system log');

        $I->amOnPage('/rbac/list-items');

        $I->see('Rbac: List items');

        $I->amOnPage('/rbac/list-parent-child');

        $I->see('Rbac: List Parent-Child');

        $I->amOnPage('/rbac/list-assignment');

        $I->see('Rbac: List of assignments');

        $I->amOnPage('/system/transits-manager-log');

        $I->see('Transits manager log');

        $I->amOnPage('/system/env');

        $I->see('env variables:');

    }
}
