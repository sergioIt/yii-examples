<?php

/**
 * Class indexCest
 */
class indexCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
    }

    public function apiIndex(ApiTester $I){

        $I->sendGET('api/');

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

        $I->seeResponseIsJson();


        $arr = [
//            'supports/available',
            'user/get-info',
            'user/get-current-info',
            'methods'];

        $I->seeResponseContainsJson($arr);
    }
}
