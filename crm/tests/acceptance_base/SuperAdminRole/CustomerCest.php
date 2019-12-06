<?php
/**
 * Created by PhpStorm.
 * User: makryun
 * Date: 30/01/2019
 * Time: 12:47
 */

namespace app\tests\acceptance_base\SuperAdminRole;


class CustomerCest
{
    /**
     * @param \Acceptance_baseTester $I
     */
    public function listIndex(\Acceptance_baseTester $I){

        $I->loggedAs('super-admin');

        $I->amOnPage('/customer/list-synced');

        $I->see('Synced customers');

        $I->amOnPage('/customer-balance-eq-deposit/list-no-cards');

        $I->see('Forbidden (#403)');

        $I->amOnPage('/customer-balance-eq-deposit/list-cards-all');

        $I->see('Forbidden (#403)');

    }
}
