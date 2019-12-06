<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 12.07.19
 * Time: 13:26
 */

namespace app\tests\acceptance_base\SuperAdminRole;

/**
 * Class CardSearchCest
 * @package app\tests\acceptance_base\SuperAdminRole
 */
class CardSearchCest
{
    /**
     * @param \Acceptance_baseTester $I
     */
    public function search(\Acceptance_baseTester $I){

        $I->loggedAs('super-admin');

        $I->amOnPage('/card/search');

        $I->see('Cards search');

        $I->see('No results found.');

        // поиск по id не существующего клиента
        $I->amOnPage('/card/search?CardCommonSearch%5Bid%5D=766553');

        $I->dontSee('exception');

        $I->see('No results found.');

        // поиск по id существующего клиента
        $I->amOnPage('/card/search?CardCommonSearch%5Bid%5D=314017');

        $I->dontSee('exception');
        $I->dontSee('No results found.');

        // поиск по телефону существующего клиента
        $I->amOnPage('/card/search?CardCommonSearch%5Bphone%5D=9363781');

        $I->dontSee('exception');
        $I->dontSee('No results found.');

        // поиск по телефону (cards.opt_phone) существующего клиента
        $I->amOnPage('/card/search?CardCommonSearch%5Bphone%5D=454545');

        $I->dontSee('exception');
        $I->dontSee('No results found.');

        $I->amOnPage('/auth/logout');

    }
}
