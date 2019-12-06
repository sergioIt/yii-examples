<?php

/**
 * Class cardCest
 */
class cardCest
{
    public $cardUrl = 'api/card';

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
    public function card(ApiTester $I){
        $I->sendOPTIONS($this->cardUrl);

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

        // попытка просто дёрнуть урл без параметров и авторизации
        $I->sendGET($this->cardUrl);

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED);

        // попытка дёрнуть урл без параметров c авторизацией
        $I->haveHttpHeader("X-Auth-Token", "ZKr1YKEy");

        $I->sendGET($this->cardUrl);

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::INTERNAL_SERVER_ERROR);

        $I->sendGET($this->cardUrl,['customer_id' => '[333460]']);

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

        $I->seeResponseIsJson();

        $data = [

            '333460' =>[

                "customer_id" => 333460,
                "user_id" => 3,
                "owner" => "seller"
            ]
        ];

        $I->seeResponseContainsJson($data);
    }

    /**
     * @param ApiTester $I
     */
    public function getInfoNoErrors(ApiTester $I){

        $I->sendGET('card-api/get-info?customer_id=700444&token=N3lsVartest');

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    }

    /**
     * @param ApiTester $I
     *
     * @group critical
     */
    public function getInfoMultipleNoErrors(ApiTester $I){

        $I->sendGET('card-api/get-info-multiple?customers_group=[720742,689771,645230]&token=N3lsVartest');

//        $I->dontSeeErrors();

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
    }
}
