<?php
use app\models\Support;

/**
 * Class LoginFormCest
 *
 * @group login
 */
class LoginFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('auth/login');
    }

    public function openLoginPage(\FunctionalTester $I)
    {
        $I->see('Please fill out the following fields to login:');

    }

    // demonstrates `amLoggedInAs` method
    public function internalLoginById(\FunctionalTester $I)
    {
        $userId = 1;
        $I->amLoggedInAs($userId);
        $I->amOnPage('/');

        $user = Support::findOne($userId);
        $I->see('(' . $user->login . ')');
    }

}
