<?php

return [
    [
        'id'          => 1,
        'created'     => 1517443200,
        'updated'     => 1517479200,
        'customer_id' => 702895,
        'billing'     => 'offline_wire_transfer', // sterling, baokim, dragonpay
        'paid_date'   => 1517479200,
        'status'      => 1,
        'currency'    => 'PHP',
        'amount'      => 5000,
        'is_first'    => 1,
    ],
    [
        'id'          => 2,
        'created'     => 1524065373,
        'updated'     => 1524065393,
        'customer_id' => 102,
        'billing'     => 'ecomm',
        'paid_date'   => 1524065556,
        'status'      => 0,
        'currency'    => 'THB',
        'amount'      => 35.00,
        'is_first'    => 0,
    ],
    [
        'id'          => 3,
        'created'     => 1517616000,
        'updated'     => 1517652000,
        'customer_id' => 702895,
        'billing'     => 'baokim',
        'paid_date'   => 1517652000,
        'status'      => 0,
        'currency'    => 'PHP',
        'amount'      => 2000,
        'is_first'    => 0,
    ],

    // ----
    [
        'id'          => 4,
        'created'     => 1525132800, // 2018-05-01 00:00:00
        'updated'     => 1525132800,
        'customer_id' => 101,
        'billing'     => 'offline_wire_transfer',
        'paid_date'   => 1525219200, // 2018-05-02 00:00:00
        'status'      => 1,
        'currency'    => 'VND',
        'amount'      => 2000.00,
        'is_first'    => 1,
        'deposit'     => '2018-07-02 00:00:00',
    ],
    [
        'id'          => 5,
        'created'     => 1525305600, // 2018-05-03 00:00:00
        'updated'     => 1525305600,
        'customer_id' => 101,
        'billing'     => 'help2pay',
        'paid_date'   => 1525356000, // 2018-05-03 14:00:00
        'status'      => 1,
        'currency'    => 'VND',
        'amount'      => 100.00,
        'is_first'    => 0,
        'deposit'     => '2018-07-03 00:00:00',
    ],
    [
        'id'          => 6,
        'created'     => 1525363200, // 2018-05-03 16:00:00
        'updated'     => 1525363200,
        'customer_id' => 101,
        'billing'     => 'sterling',
        'paid_date'   => 1525370400, // 2018-05-03 18:00:00
        'status'      => 1,
        'currency'    => 'VND',
        'amount'      => 4050.00,
        'is_first'    => 1, // двух "первых" быть не должно, но такое есть - тестим, что ориентируемся только на один
        'deposit'     => '2018-07-03 16:00:00',
    ],
    [
        'id'          => 7,
        'created'     => 1525428000, // 2018-05-04 10:00:00
        'updated'     => 1525428000,
        'customer_id' => 101,
        'billing'     => 'sterling',
        'paid_date'   => 1525690800, // 2018-05-07 11:00:00
        'status'      => 1,
        'currency'    => 'VND',
        'amount'      => 6010.00,
        'is_first'    => 0,
        'deposit'     => '2018-07-04 10:00:00',
    ],
    [
        'id'          => 8,
        'created'     => 1525834800, // 2018-05-09 03:00:00
        'updated'     => 1525834800,
        'customer_id' => 101,
        'billing'     => 'sterling',
        'paid_date'   => 1525845600, // 2018-05-09 06:00:00
        'status'      => 0,         // exclude bonus
        'currency'    => 'VND',
        'amount'      => 2145.00,
        'is_first'    => 0,
        'deposit'     => '2018-07-09 03:00:00',
    ],
    [
        'id'          => 9,
        'created'     => 1525910400, // 2018-05-10 12:00:00
        'updated'     => 1525910400,
        'customer_id' => 101,
        'billing'     => 'atm',
        'paid_date'   => 1526014800, // 2018-05-11 05:00:00
        'status'      => 1,
        'currency'    => 'PHP',
        'amount'      => 130.00,
        'is_first'    => 0,
        'deposit'     => '2018-07-11 05:00:00',
    ],
    [
        'id'          => 10,
        'created'     => 1525910400, // 2018-05-10 12:00:00
        'updated'     => 1525910400,
        'customer_id' => 101,
        'billing'     => 'atm',
        'paid_date'   => 1526014800, // 2018-05-11 05:00:00
        'status'      => 1,
        'currency'    => 'PHP',
        'amount'      => 160.00,
        'is_first'    => 0,
        'deposit'     => '2018-07-22 05:00:00',
    ],
    // платёж клиента для тестирования  CustomerTypeServiceTest для типа real asleep
    [
        'id'          => 11,
        'created'     => 1525910400, // 2018-05-10 12:00:00
        'updated'     => 1525910400,
        'customer_id' => 766555,
        'billing'     => 'atm',
        'paid_date'   => 1526014800, // 2018-05-11 05:00:00
        'status'      => 1,
        'currency'    => 'VND',
        'amount'      => 160.00,
        'is_first'    => 1,
        'deposit'     => '2018-07-22 05:00:00',
    ],
    // платёж клиента для тестирования  CustomerTypeServiceTest для типа real no trades
    [
        'id'          => 12,
        'created'     => 1525910400, // 2018-05-10 12:00:00
        'updated'     => 1525910400,
        'customer_id' => 766556,
        'billing'     => 'atm',
        'paid_date'   => 1526014800, // 2018-05-11 05:00:00
        'status'      => 1,
        'currency'    => 'VND',
        'amount'      => 180.00,
        'is_first'    => 1,
        'deposit'     => '2018-07-22 05:00:00',
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testFailedPaymentsWhichIsLastQuery()
     */
    [
        'id'          => 13,
        'created'     => 1525910400, // 2018-05-10 12:00:00
        'updated'     => 1525910400,
        'customer_id' => 111,
        'billing'     => 'atm',
        'paid_date'   => 1526014800, // 2018-05-11 05:00:00
        'status'      => 2,
        'currency'    => 'PHP',
        'amount'      => 160.00,
        'is_first'    => 0,
        'deposit'     => '2018-07-23 05:00:00',
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testLastApprovedPaymentQuery()
     */
    [
        'id'          => 14,
        'created'     => 1525910400, // 2018-05-10 12:00:00
        'updated'     => 1525910400,
        'customer_id' => 111,
        'billing'     => 'atm',
        'paid_date'   => 1526014800, // 2018-05-11 05:00:00
        'status'      => 1,
        'currency'    => 'PHP',
        'amount'      => 270.00,
        'is_first'    => 0,
        'deposit'     => '2018-07-21 05:00:00',
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testRecentPaymentFailedQueryResult()
     */
    [
        'id'          => 15,
        'created'     => 1525910400, // 2018-05-10 12:00:00
        'updated'     => 1525910400,
        'customer_id' => 126,
        'billing'     => 'atm',
        'paid_date'   => 1526014800, // 2018-05-11 05:00:00
        'status'      => 0,
        'currency'    => 'VND',
        'amount'      => 270.00,
        'is_first'    => 0,
        'deposit'     => '2019-05-21 05:00:00',
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testRecentPaymentFailedQueryResult()
     */
    [
        'id'          => 16,
        'created'     => 1525910400, // 2018-05-10 12:00:00
        'updated'     => 1525910400,
        'customer_id' => 126,
        'billing'     => 'atm',
        'paid_date'   => 1526014800, // 2018-05-11 05:00:00
        'status'      => 2,
        'currency'    => 'VND',
        'amount'      => 270.00,
        'is_first'    => 0,
        'deposit'     => '2019-05-22 05:00:00',
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testRecentPaymentFailedQueryResult()
     */
    [
        'id'          => 17,
        'created'     => 1525910400, // 2018-05-10 12:00:00
        'updated'     => 1525910400,
        'customer_id' => 127,
        'billing'     => 'atm',
        'paid_date'   => 1526014800, // 2018-05-11 05:00:00
        'status'      => 0,
        'currency'    => 'VND',
        'amount'      => 270.00,
        'is_first'    => 0,
        'deposit'     => '2019-05-22 05:00:00',
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testGetRealStoppedQueryResult()
     */
    [
        'id'          => 18,
        'created'     => 1525910400, // 2018-05-10 12:00:00
        'updated'     => 1525910400,
        'customer_id' => 128,
        'billing'     => 'atm',
        'paid_date'   => 1526014800,
        'status'      => 1,
        'currency'    => 'VND',
        'amount'      => 270.00,
        'is_first'    => 0,
        'deposit'     => '2019-05-22 05:00:00',
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testGetRealStoppedQueryResult()
     */
    [
        'id'          => 19,
        'created'     => 1525910400, // 2018-05-10 12:00:00
        'updated'     => 1525910400,
        'customer_id' => 128,
        'billing'     => 'atm',
        'paid_date'   => 1526014900,
        'status'      => 1,
        'currency'    => 'VND',
        'amount'      => 5000.00,
        'is_first'    => 0,
        'deposit'     => '2019-05-23 05:00:00',
    ],
 [
        'id'          => 20,
        'created'     => 1525910400, // 2018-05-10 12:00:00
        'updated'     => 1525910400,
        'customer_id' => 129,
        'billing'     => 'atm',
        'paid_date'   => 1526014900,
        'status'      => 1,
        'currency'    => 'VND',
        'amount'      => 500.00,
        'is_first'    => 1,
        'deposit'     => '2019-05-23 05:00:00',
    ],

];
