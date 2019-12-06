<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 30.05.19
 * Time: 18:54
 */

namespace app\tests\acceptance_base\SuperAdminRole;


class TaskCest
{
    public function list(\Acceptance_baseTester $I)
    {

        $I->loggedAs('super-admin');

        $I->amOnPage('/task/list');

        $I->see('All task list');

        $I->see('email was sent'); // тест на то, что виден последний комметн на главной странице

        $I->see('task from super-admin');

        $I->amOnPage('/task/list?TaskSearch%5Blast_comment%5D=notified'); // тест на поиск по тексту таска

        $I->see('Showing 1-1 of 1 item');

        $I->amOnPage('/task/list?TaskSearch%5Blast_comment%5D=first comment'); // тест на поиск по тексту комметов

        $I->see('Showing 1-1 of 1 item');

    }


}
