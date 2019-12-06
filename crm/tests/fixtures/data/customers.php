<?php

return [
    [
        'id' => 101,
        'user_key' => '5a9e97fcaa3457.35667382.xbb0e0',
        'active' => \app\models\Customer::STATE_ACTIVE,
        'reg_date' => 1520343036,
        'currency' => 'THB',
    ],
    [
        'id' => 102,
        'user_key' => '5a9e97fcaa3457.35667388.xbb0e0',
        'active' => \app\models\Customer::STATE_ACTIVE,
        'reg_date' => 1520343096,
        'currency' => 'THB',
    ],
    [
        'id' => 103,
        'user_key' => '5a9e97fcaa3457.35667388.xbb0e1',
        'active' => \app\models\Customer::STATE_ACTIVE,
        'reg_date' => 1520343092,
        'currency' => 'THB',
    ],
    [
        'id' => 104,
        'user_key' => '5a9e97fcaa3457.35667388.xbb0e2',
        'active' => \app\models\Customer::STATE_ACTIVE,
        'reg_date' => 1520343033,
        'currency' => 'THB',
    ],
    [
        'id'       => 105,
        'user_key' => '5a9e97fcaa3457.35667388.xbb0e6',
        'active'   => \app\models\Customer::STATE_ACTIVE,
        'reg_date' => 1520343037,
        'currency' => 'VND',

        'real' => 1,
        'last_seen' => 1552572978,
        'status' => 2, # verified
        'first_name' => 'Customer #105',
        'email' => 'customer105@irontrade.com',
        'phone' => '89998887766',
    ],

    /**
     * @see CardServiceTest::testAutoDeclineByHotKeys()
     *
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testDemoCommonResult()
     */
    [
        'id'       => 109,
        'user_key' => '5a9e97fcaa3457.35667388.xbb0e9',
        'active'   => \app\models\Customer::STATE_ACTIVE,
        'reg_date' => 1520343037,
        'currency' => 'THB',

        'real' => 0, #demo
        'last_seen' => 1552572978,
        'status' => 2, # verified
        'first_name' => 'Customer #109',
        'email' => 'customer109@irontrade.com',
        'phone' => '89998887711',
    ],
    /**
     * @see AsteriskApiHelperTest::testUpdateNotPhone()
     * случай создания новой карточки
     *
     *  @see \app\tests\unit\services\CustomerQueryServiceTest::testDemoCommonResult()
     */
    [
        'id'       => 114,
        'user_key' => '5a9e97fcaa3457.35667388.xbb0e9',
        'active'   => \app\models\Customer::STATE_ACTIVE,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 0, #demo
        'last_seen' => 1552572978,
        'status' => 2, # verified
        'first_name' => 'Customer #111',
        'email' => 'customer109@irontrade.com',
        'phone' => '89998887711',
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testDemoCommonResult()
     */
    [
        'id'       => 112,
        'user_key' => '5a9e97fcaa3457.35667388.xbb0e9',
        'active'   => \app\models\Customer::STATE_ACTIVE,
        'reg_date' => 1520256637,
        'currency' => 'VND',
        'real' => 0, #demo
        'last_seen' => 1552572978,
        'first_name' => 'Customer #111',
        'email' => 'customer123@irontrade.com',
        'phone' => '+84998887711',
        'card_id' => null,
        'callcenter_status' => 2,
    ],
    /**
     * @see \tests\models\CardProducerTest::testGetForRoboCall()
     * @see \tests\models\CardProducerTest::createForRobocallDataProvider()
     */
    [
        'id'       => 115,
        'user_key' => '5a9e97fcaa3457.35667388.xbb0e9',
        'active'   => \app\models\Customer::STATE_ACTIVE,
        'reg_date' => 1520343037,
        'currency' => 'USD',
        'real' => 1,
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testDemoBaseQuery()
     */
    [
        'id'       => 116,
        'user_key' => '5a9e97fcaa3457.35667388.xbb0e9',
        'active'   => \app\models\Customer::STATE_ACTIVE,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 0,
        'card_id' => 200,
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testDemoBaseQuery()
     */
    [
        'id'       => 117,
        'user_key' => '5a9e97fcaa3457.35667388.xbb0e9',
        'active'   => \app\models\Customer::STATE_ACTIVE,
        'reg_date' => 1520343037,
        'currency' => 'VND',
        'real' => 0,
        'processing_card' => true,
    ],
];
