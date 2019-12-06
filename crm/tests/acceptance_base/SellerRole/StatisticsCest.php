<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 20.06.19
 * Time: 18:26
 */

namespace app\tests\acceptance_base\SellerRole;


class StatisticsCest
{
    public function listIndex(\Acceptance_baseTester $I){

        $I->loggedAs('seller');

        $I->amOnPage('/card-actions/list-my');

        $I->see('My cards actions');

        $I->amOnPage('/seller-statistics/actions-personal');

        $I->see('My actions statistics');

    }
}
