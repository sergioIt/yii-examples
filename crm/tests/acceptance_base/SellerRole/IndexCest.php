<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 23.04.19
 * Time: 18:31
 */

namespace app\tests\acceptance_base\SellerRole;

/**
 * Class IndexCest
 * @package app\tests\acceptance_base\SellerRole
 */
class IndexCest
{
    public function index(\Acceptance_baseTester $I){

        $I->loggedAs('seller');

        $I->amOnPage('/');

        $I->dontSee('exception');

    }
}
