<?php

/**
 * Class userCest
 */
class userCest
{

    public $userGetInfoUrl = 'api/user-info';

    public $userGetInfoUrlAlias = 'api/user/get-info';

    public $userGetCurrentInfoUrl = 'api/current-user-info';

    public $userGetCurrentInfoUrlAlias = 'api/user/get-current-info';

    public $usersAllUrl = 'api/all-users';

    public $usersAllUrlAlias = 'api/users/all';


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


    /**
     * @param ApiTester $I
     */
    public function userInfo(ApiTester $I){

        $I->sendOPTIONS($this->userGetInfoUrl);

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

        $I->sendOPTIONS($this->userGetInfoUrlAlias);

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

        $I->sendGET($this->userGetInfoUrl);

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED);

        $I->haveHttpHeader("X-Auth-Token", "ZKr1YKEy");

        $I->sendGET($this->userGetInfoUrl,['id' => 2]);

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

        $I->seeResponseIsJson();

        $I->seeResponseContainsJson(['id' => 2, 'login' => 'admin_another']);
    }

}
