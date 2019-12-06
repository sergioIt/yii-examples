<?php
/**
 * Created by PhpStorm.
 * User: makryun
 * Date: 30/01/2019
 * Time: 13:10
 */

namespace app\tests\acceptance_base\SuperAdminRole;


class QueueCest
{

    public function index(\Acceptance_baseTester $I){

        $I->loggedAs('super-admin');

        $I->amOnPage('/queue/manager');

        $I->see('Counters for all currencies available for selected sellers');

        $I->dontSee('error');
    }
}
