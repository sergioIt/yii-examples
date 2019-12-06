<?php
/**
 * Created by PhpStorm.
 * User: makryun
 * Date: 30/01/2019
 * Time: 13:02
 */

namespace app\tests\acceptance_base\SuperAdminRole;

/**
 * Class CallCest
 * @package app\tests\acceptance_base\SuperAdminRole
 */
class CallCest
{
    public function fixtures(): array
    {
        return [
        ];
    }

    /**
     * @param \Acceptance_baseTester $I
     */
    public function listIndex(\Acceptance_baseTester $I){

        $I->loggedAs('super-admin');

        $I->amOnPage('/call/timeline');

        $I->see('Call time line');

        $I->amOnPage('/call/list');

        $I->see('Call list');
    }
}
