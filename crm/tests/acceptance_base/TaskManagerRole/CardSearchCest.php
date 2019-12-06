<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 12.07.19
 * Time: 13:29
 */

namespace app\tests\acceptance_base\TaskManagerRole;


class CardSearchCest
{
    /**
     * @param \Acceptance_baseTester $I
     */
    public function index(\Acceptance_baseTester $I){

        $I->loggedAs('task-manager');

        $I->amOnPage('/card/search');

        $I->see('Forbidden');

        // поиск по телефону существующего клиента
        $I->amOnPage('/card/search?CardCommonSearch%5Bphone%5D=9363781');

        $I->dontSee('exception');
        $I->dontSee('No results found.');

        $I->amOnPage('/auth/logout');
    }
}
