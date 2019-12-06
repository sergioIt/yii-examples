<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 12.07.19
 * Time: 13:29
 */

namespace app\tests\acceptance_base\SupportRole;


class CardSearchCest
{
    /**
     * @param \Acceptance_baseTester $I
     */
    public function index(\Acceptance_baseTester $I){

        $I->loggedAs('support');

        $I->amOnPage('/card/search');

        $I->see('Cards search');

        $I->see('No results found.');

        // поиск по телефону существующего клиента
        $I->amOnPage('/card/search?CardCommonSearch%5Bphone%5D=9363781');

        $I->dontSee('exception');
        $I->dontSee('No results found.');

        $I->amOnPage('/auth/logout');
    }
}
