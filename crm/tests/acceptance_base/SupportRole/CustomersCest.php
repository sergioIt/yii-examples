<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 14.05.19
 * Time: 15:48
 */

namespace app\tests\acceptance_base\AdminRole;


class CustomersCest
{

    /**
     * @param \Acceptance_baseTester $I
     */
    public function listIndex(\Acceptance_baseTester $I){

        $I->loggedAs('support');

        $I->amOnPage('/customer-balance-eq-deposit/list-no-cards');

        $I->see('Forbidden (#403)');

        $I->amOnPage('/customer-balance-eq-deposit/list-cards-all');

        $I->see('Forbidden (#403)');

    }
}
