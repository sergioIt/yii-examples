<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 14.05.19
 * Time: 15:49
 */

namespace app\tests\acceptance_base\AdminRole;


class StatisticsCest
{
    public function index(\Acceptance_baseTester $I){

        $I->loggedAs('admin');

        $I->amOnPage('/card-actions/list');

        $I->see('All cards actions');

        $I->amOnPage('/seller-statistics/actions-all');

        $I->see('Sellers actions summary');


        $I->amOnPage('/seller-statistics/actions-daily');

        $I->see('Sellers actions daily');

    }
}
