<?php

/**
 * Created by PhpStorm.
 * User: makryun
 * Date: 19/03/2019
 * Time: 13:17
 */
class CallCest
{

    /**
     * @param \Acceptance_baseTester $I
     */
    public function listIndex(\Acceptance_baseTester $I){

        $I->loggedAs('admin');

        $I->amOnPage('/call/timeline');

        $I->see('Call time line');

    }

}
