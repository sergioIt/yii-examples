<?php
/**
 * Created by PhpStorm.
 * User: makryun
 * Date: 30/01/2019
 * Time: 12:55
 */

namespace app\tests\acceptance_base\SuperAdminRole;

/**
 * Class UserCest
 * @package app\tests\acceptance_base\SuperAdminRole
 */
class UserCest
{


    public function _before(\Acceptance_baseTester $I)
    {
        //  Check the content of fixtures in db
        // seeRecord выдаёт ошибку в гитлабе [RuntimeException]Action 'seeRecord' can't be called
        // так как пока что не удаётся загружать фикстуры в базу asterisk, то проерка на наличие записей пока что не делается
    }

    /**
     * @param \Acceptance_baseTester $I
     */
    public function listIndex(\Acceptance_baseTester $I){

        $I->loggedAs('super-admin');

        $I->amOnPage('/user/list');

        $I->see('Users list');

//        $I->amOnPage('/user/list-asterisk');
//
//        $I->see('Asterisk users');

        // так как пока что не удаётся загружать фикстуры в базу asterisk, то проерка на наличие записей пока что не делается
//        $I->see('Showing 1-2 of 2');
    }
}
