<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 15.03.19
 * Time: 17:28
 */

namespace app\tests\acceptance_base\SellerRole;

/**
 * Class CardSearchCest
 * @package app\tests\acceptance_base\SellerRole
 */
class CardSearchCest
{

    /**
     * @param \Acceptance_baseTester $I
     */
    public function index(\Acceptance_baseTester $I){

        $I->loggedAs('seller');

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
