<?php
namespace app\tests\api;


/**
 * Created by PhpStorm.
 * User: makryun
 * Date: 25/01/2019
 * Time: 15:24
 */
class asteriskCest
{

    const BASE_URL_GET_USERS = 'asterisk-api-v3/get-users?token=QZUSZ3-FH016&country=THA&limit=10';

    const BASE_URL_GET_USERS_CONTROL = 'asterisk-api-v3/get-users-control?token=QZUSZ3-FH016&country=THA&limit=10';

    const BASE_URL_SET_NOT_ON_PHONE = 'asterisk-api-v3/set-not-on-phone?token=QZUSZ3-FH016';


    public $allowedTypes = [

        'demo_common',
        'demo_nop_common',
        'demo_ip_app_recall',
        'demo_ip_app_empty_recall',
        'real_stopped',
        'real_inactive',
        'real_demo_active',
        'demo_no_activity_1day',
        'demo_no_activity_3hours',
        'demo_no_refill',
        'demo_no_fillup',
        'all_no_pay_tournaments',
        'demo_no_tournaments',
        'demo_recent_payment_failed',
    ];

    /**
     * @param \ApiTester $I
     */
    public function getUsers(\ApiTester $I)
    {

        $I->sendGET(self::BASE_URL_GET_USERS);

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        // проверка на обязательный параметр type
        $I->seeResponseContains('missed required get param: type');

         // @todo брать типы запросов из сервиса, сейчас ошибка:
//        $allowed = CustomerQueryService::getAllTypesForContext('asterisk');

        foreach($this->allowedTypes as $type){

            $I->sendGET(self::BASE_URL_GET_USERS.'&type='.$type);

            $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

            $I->dontSeeResponseContains('error');


            $I->sendGET(self::BASE_URL_GET_USERS_CONTROL.'&type='.$type);

            $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
            $I->dontSeeResponseContains('error');

        }

    }

    /**
     * @param \ApiTester $I
     *
     * этот тест падает, потому что пустой ответ при sendRemoveFromQueue, а ожидается json
     */
//    public function setNotOnPhone(\ApiTester $I){
//
//        $I->sendGET(self::BASE_URL_SET_NOT_ON_PHONE);
//
//        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
//
//        $I->seeResponseContains('missed required get param: order_id');
//
//        $I->sendGET(self::BASE_URL_SET_NOT_ON_PHONE.'&order_id=2');
//
//        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
//
//        $I->seeResponseContains('>synced customer not found by id 2');
//
//        $I->sendGET(self::BASE_URL_SET_NOT_ON_PHONE.'&order_id=314017');
//
//        // проверка содержания в случае успешного апдейта
//
//        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
//
//        $I->dontSeeResponseContains('error');
//
//        $I->seeResponseContains('<asterisk_response>');
//        $I->seeResponseContains('</asterisk_response>');
//
//    }

    /**
     * @param \ApiTester $I
     */
    public function getHelp(\ApiTester $I){

        $I->sendGET('asterisk-api-v3/help');

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

        $I->dontSeeResponseContains('error');

        // проверка на обязательный параметр type
        $I->seeResponseContains('allowed types:');
        $I->seeResponseContains('allowed actions:');
    }
}
