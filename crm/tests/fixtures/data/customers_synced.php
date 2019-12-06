<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 26.09.18
 * Time: 20:13
 */

return [
    [
        'id' => 76653,
        'reg_date' => 1520348242,
        'currency' => 'THB',
    ],
    [
        'id' => 766552,
        'reg_date' => 1520348242,
        'currency' => 'VND',
        'phone' => '7775543210',
    ],
    [
        'id' => 766553,
        'reg_date' => 1520348242,
        'currency' => 'VND',
        'first_name' => 'Sergio',
    ],
    // для тестирования типа клиента demo active
    [
        'id' => 766554,
        'reg_date' => 1520348242,
        'currency' => 'VND',
        'op_count_demo' => 2,
    ],

    // для тестирования типа клиента real asleep
    [
        'id' => 766555,
        'reg_date' => 1520348242,
        'currency' => 'VND',
        'op_count_demo' => 2,
        'real' => 1,
        'phone' => '766455484',
        'email' => 'test766555@email.com',
        'balance_real' => 200,
        'active' => 1,
        'last_operation_real' => 1536944043,
        'last_operation_demo' => 1536944000
    ],

    // для тестирования типа клиента real stopped
    [
        'id' => 766556,
        'reg_date' => 1520348242,
        'currency' => 'VND',
        'op_count_demo' => 2,
        'real' => 1,
        'phone' => '766455484',
        'email' => 'test@email.com',
        'balance_real' => 200,
        'active' => 1,
        'last_operation_real' => null
    ],
    // для CustomerScriptServiceTest
    [
        'id' => 107,
        'reg_date' => 1520348242,
        'currency' => 'VND',
        'op_count_demo' => 2,
        'real' => 1,
        'phone' => '766455484',
        'email' => 'test@email.com',
        'balance_real' => 200,
        'active' => 1,
        'last_operation_real' => null
    ],
    /**
     * @see \app\tests\unit\helpers\AsteriskApiHelperTest::testUpdateNotPhone()
     */
    [
        'id' => 106,
        'reg_date' => 1520348242,
        'currency' => 'VND',
        'profit_percent' => 1

    ],
    /**
     * @see CardServiceTest::testProcessAutoDecline()
     */
    [
        'id' => 101,
        'reg_date' => 1520348242,
        'currency' => 'THB',
        'op_count_demo' => 2,
        'real' => 0,
    ],
    // для тестировния NextCustomerServiceTest->testDefineQueueForUser
    /**
     * @see \tests\services\CustomersServiceTest::testUpdateMainData()
     * @see \tests\services\CustomersServiceTest::testUpdateOperations()
     */
    [
        'id' => 108,
        'reg_date' => 1520348242,
        'currency' => 'VND',
        'op_count_demo' => 2,
        'real' => 0,
        'phone' => '766455484',
        'email' => 'test@email.com',
        'active' => 1,
        'last_operation_real' => null
    ],

    /**
     * @see CardServiceTest::testAutoDeclineByHotKeys()
     * @see \tests\services\CustomersServiceTest::testUpdateOperations()
     */
    [
        'id'            => 109,
        'reg_date'      => 1520343037,
        'currency'      => 'THB',
        'real'          => 0,
        'email'         => 'customer109@irontrade.com',
        'phone'         => '89998887711',
        'active'        => 1,
    ],

    /**
     * @see \app\tests\unit\helpers\HotKeyHelperTest::testGetStatQuery()
     */
    [
        'id'            => 110,
        'reg_date'      => 1520343037,
        'currency'      => 'THB',
        'op_count_demo' => 2,
        'real'          => 0,
        'email'         => 'customer109@irontrade.com',
        'phone'         => '89998887711',
        'active'        => 1,
    ],
    /**
     * @see NextCustomerServiceTest::testGetNextCustomerDataForUser()
     */
    [
        'id'       => 111,
        'active'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 0, #demo
        'last_seen' => 1552572978,
        'first_name' => 'Customer #111',
        'email' => 'customer111@irontrade.com',
        'phone' => '+84998887711',
    ],
    [
        'id'       => 112,
        'active'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 0, #demo
        'last_seen' => 1552572978,
        'first_name' => 'Customer #111',
        'email' => 'customer112@irontrade.com',
        'phone' => '+84998887711',
        'phone_valid' => true
    ],

    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testGetQueryForContextNexCard
     */
    [
        'id'       => 113,
        'active'   => 1,
        'status'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'INR',
        'real' => 0, #demo
        'last_seen' => 1552572978,
        'email' => 'customer113@irontrade.com',
        'phone' => '+919988877112',
        'phone_valid' => true
    ],
    /**
     * @see AsteriskApiHelperTest::testUpdateNotPhone()
     * слуай создания новой карточки
     */
    [
        'id'       => 114,
        'active'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 0, #demo
        'last_seen' => 1552572978,
        'first_name' => 'Customer #111',
        'email' => 'customer114@irontrade.com',
        'phone' => '+84998887711',
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testGetDemoCardNotOnPhoneQuery()
     */
    [
        'id'       => 116,
        'active'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 0, #demo
        'last_seen' => 1552572978,
        'first_name' => 'Customer #115',
        'email' => 'customer115@irontrade.com',
        'phone' => '+84998887711',
        'phone_valid' => true
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testDemoNoActivityOneDayResult()
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testDemoNoActivityThreeHoursResult()
     */
    [
        'id'       => 117,
        'active'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 0, #demo
        'last_seen' => 1552572978,
        'first_name' => 'Customer #117',
        'email' => 'customer117@irontrade.com',
        'phone' => '+84998887711',
        'last_operation_real' => null,
        'last_operation_demo' => null,
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testDemoNoFillupQueryResult()
     */
    [
        'id'       => 118,
        'active'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 0,
        'last_seen' => 1552572978,
        'first_name' => 'Customer #119',
        'email' => 'customer119@irontrade.com',
        'phone' => '+84998887711',
        'last_operation_demo' => 1559053740,
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testDemoNoFillupQueryResult()
     */
    // real клиент с demo операциями, нужен для проверки, что он не попадает в выборку
    [
        'id'       => 119,
        'active'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 1,
        'last_seen' => 1552572978,
        'first_name' => 'Customer #117',
        'email' => 'customer117@irontrade.com',
        'phone' => '+84998887711',
        'last_operation_demo' => 1559053740,
    ],
    // demo клиент с турнирными операциями, нужен для проверки, что он не попадает в выборку
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testDemoNoTournamentsQueryResult()
     */
    [
        'id'       => 120,
        'active'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 0,
        'last_seen' => 1552572978,
        'first_name' => 'Customer #120',
        'email' => 'customer120@irontrade.com',
        'phone' => '+84998887711',
        'last_operation_demo' => 1559053740,
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testDemoNoRefillQueryResult()
     */
    // demo клиент с demo операциями, с балансом меньше 10% от начального депозита, должен попасть в выборку getDemoNoRefillQuery
    [
        'id'       => 121,
        'active'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 0,
        'last_seen' => 1552572978,
        'first_name' => 'Customer #121',
        'email' => 'customer121@irontrade.com',
        'phone' => '+84998887711',
        'last_operation_demo' => 1559053740,
        'balance_demo' => '390.00',
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testDemoNoRefillQueryResult()
     */
    // demo клиент с demo операциями, с балансом больше 10% от начального депозита, должен попасть в выборку getDemoNoRefillQuery
    [
        'id'       => 122,
        'active'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 0,
        'last_seen' => 1552572978,
        'first_name' => 'Customer #122',
        'email' => 'customer122@irontrade.com',
        'phone' => '+84998887711',
        'last_operation_demo' => 1559053740,
        'balance_demo' => '411.00',
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testAllNoPayTournamentsQueryResult()
     */
    // demo клиент, зарегистрированный в бесплатном и не зарегистриованный в платном турнире
    [
        'id'       => 123,
        'active'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 0,
        'last_seen' => 1552572978,
        'first_name' => 'Customer #123',
        'email' => 'customer122@irontrade.com',
        'phone' => '+84998887711',
        'last_operation_demo' => 1559053740,
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testAllNoPayTournamentsQueryResult()
     */
    // demo клиент, зарегистрированный в платном турнире
    [
        'id'       => 124,
        'active'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 0,
        'last_seen' => 1552572978,
        'first_name' => 'Customer #124',
        'email' => 'customer122@irontrade.com',
        'phone' => '+84998887711',
        'last_operation_demo' => 1559053740,
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testAllNoPayTournamentsQueryResult()
     */
    // real клиент,  зарегистрированный в бесплатном и не зарегистриованный в платном турнире
    [
        'id'       => 125,
        'active'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 1,
        'last_seen' => 1552572978,
        'first_name' => 'Customer #125',
        'email' => 'customer122@irontrade.com',
        'phone' => '+84998887711',
        'last_operation_demo' => 1559053740,
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testRecentPaymentFailedQueryResult()
     *
     * @see \app\tests\unit\models\\app\tests\unit\models\CustomersTest::testBalanceLabel()
     */
    // demo клиент, у которого последний платёж с ошибкой (статус платежа 2 или 3)
    [
        'id'       => 126,
        'active'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 0,
        'last_seen' => 1552572978,
        'first_name' => 'Customer #125',
        'email' => 'customer122@irontrade.com',
        'phone' => '+84998887711',
        'last_operation_demo' => 1559053740,
    ],
    /**
     * demo клиент, у которого последний платёж pending (статус 0)
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testRecentPaymentFailedQueryResult()
     *
     *  @see \app\tests\unit\models\\app\tests\unit\models\CustomersTest::testBalanceLabel()
     */
    [
        'id'       => 127,
        'active'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 0,
        'last_seen' => 1552572978,
        'first_name' => 'Customer #125',
        'email' => 'customer122@irontrade.com',
        'phone' => '+84998887711',
        'last_operation_demo' => 1559053740,
        'balance_demo' => '128000.00'
    ],
    /**
     * real клиент, у которого реальный баланс больше чем 10% от последнего успешного платёжа (450 < 500)
     *
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testGetRealStoppedQueryResult()
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testGetRealInactiveQueryResult()
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testGetRealDemoActiveQueryResult()
     */
    //
    [
        'id'       => 128,
        'active'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 1,
        'last_seen' => 1552572978,
        'first_name' => 'Customer #128',
        'email' => 'customer128@irontrade.com',
        'phone' => '+84998887711',
        'last_operation_demo' => 1559053740,
        'last_operation_real' => 1559053740,
        'balance_real' => 550,
    ],
    /**
     * real клиент, у которого реальный баланс меньше чем 10% от последнего успешного платёжа (45 > 50)
     *
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testGetRealStoppedQueryResult()
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testGetRealInactiveQueryResult()
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testGetRealDemoActiveQueryResult()
     */
    [
        'id'       => 129,
        'active'   => 1,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 1,
        'last_seen' => 1552572978,
        'first_name' => 'Customer #129',
        'email' => 'customer129@irontrade.com',
        'phone' => '+84998887711',
        'last_operation_demo' => 1559053740,
        'last_operation_real' => 1559053740,
        'balance_real' => 45,
    ],
    /**
     * @see \tests\services\UserServiceTest::testActive()
     */
    [
        'id' => 130,
        'active' => 1,
        'reg_date' => 1520343037,
    ],
];
