<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 09.04.19
 * Time: 18:44
 */

namespace app\tests\acceptance_base\SuperAdminRole;


class StatisticsCest
{
    /**
     * @param \Acceptance_baseTester $I
     */
    public function index(\Acceptance_baseTester $I){

        $I->loggedAs('super-admin');

        $I->amOnPage('/card-actions/list');

        $I->see('All cards actions');

        $I->amOnPage('/seller-statistics/actions-all');

        $I->see('Sellers actions summary');

        $I->amOnPage('/seller-statistics/actions-total');

        $I->see('Total card actions statistics');
        $I->see('Actual cards state');


        $I->amOnPage('/seller-statistics/actions-daily');

        $I->see('Sellers actions daily');

        $I->amOnPage('/verification-statistics/by-day');

        $I->see('Verification stat by day');

        $I->amOnPage('/verification-statistics/by-admin');

        $I->see('Verification statistics');

        $I->amOnPage('/verification-statistics/actions-daily');

        $I->see('Verification actions daily');

        $I->amOnPage('/verification-statistics/actions-list');

        $I->see('Verification statistics actions list');

        $I->amOnPage('/payment-verification-statistics/status-changes-list');

        $I->see('Offline payment verification actions list (status changes)');

        $I->amOnPage('/payment-verification-statistics/status-changes-by-date');

        $I->see('Offline payment status changes by date');

        $I->amOnPage('/payment-verification-statistics/status-changes-by-admin');

        $I->see('Offline payment status changes by admin');

        $I->amOnPage('/support-statistics/abandonment-rate');

        $I->see('Abandonment rate (AR)');

        $I->amOnPage('/support-statistics/speed-of-answer');

        $I->see('Avg speed of answer (ASA)');

        $I->amOnPage('/support-statistics/occupancy');

        $I->see('Occupancy');

        $I->amOnPage('/support-statistics/full-call-resolution');

        $I->see('First call resolution');

        $I->amOnPage('/support-statistics/service-level');

        $I->see('Service level (SL)');

        $I->amOnPage('/support-statistics/ping');

        $I->see('Users ping statistics');

    }
}
