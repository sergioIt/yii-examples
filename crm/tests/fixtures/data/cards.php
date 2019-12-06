<?php

return [
    [
        'id'          => 101,
        'created'     => '2018-05-01 16:26:25',
        'updated'     => '2016-05-01 11:22:33',
        'user_id'     => 2,
        'customer_id' => 101,
        'status'      => \app\models\Card::STATUS_NEW,
    ],
    [
        'id'          => 102,
        'created'     => '2018-05-01 16:26:26',
        'updated'     => '2016-05-04 11:22:34',
        'user_id'     => 2,
        'customer_id' => 102,
        'status'      => \app\models\Card::STATUS_APPROVE,
    ],
    [
        'id'          => 103,
        'created'     => '2018-06-01 10:10:10',
        'updated'     => '2016-06-01 11:11:11',
        'user_id'     => 3,
        'customer_id' => 103,
        'status'      => \app\models\Card::STATUS_IN_PROGRESS,
    ],
    [
        'id'          => 104,
        'created'     => '2018-06-02 12:12:12',
        'updated'     => '2016-06-02 13:13:13',
        'user_id'     => 4,
        'customer_id' => 104,
        'status'      => \app\models\Card::STATUS_IN_PROGRESS,
    ],
    // карчтока для тестирования изменений recall_date
    [
        'id'          => 106,
        'created'     => '2018-06-02 12:12:12',
        'updated'     => '2018-06-02 13:13:13',
        'user_id'     => 4,
        'customer_id' => 106,
        'status'      => \app\models\Card::STATUS_IN_PROGRESS,
    ],
    // для CustomerScriptServiceTest
    [
        'id'          => 107,
        'created'     => '2018-06-02 12:12:12',
        'updated'     => '2018-06-02 13:13:13',
        'user_id'     => 4,
        'customer_id' => 107,
        'status'      => \app\models\Card::STATUS_IN_PROGRESS,
    ],
    // для тестировния NextCustomerServiceTest->testDefineQueueForUser
    [
        'id'          => 108,
        'created'     => '2018-06-02 12:12:12',
        'updated'     => '2018-06-02 13:13:13',
        'user_id'     => 2,
        'customer_id' => 108,
        'status'      => \app\models\Card::STATUS_IN_PROGRESS,
    ],

    /**
     * @see CardServiceTest::testAutoDeclineByHotKeys()
     */
    [
        'id'          => 109,
        'created'     => '2018-06-01 10:10:10',
        'updated'     => '2016-06-01 11:11:11',
        'user_id'     => 5,
        'customer_id' => 109,
        'status'      => \app\models\Card::STATUS_FAKE,
    ],
    /**
     * @see \app\tests\unit\helpers\HotKeyHelperTest::testGetStatQuery()
     */
    [
        'id'          => 110,
        'created'     => '2018-06-01 10:10:10',
        'updated'     => '2016-06-01 11:11:11',
        'user_id'     => 5,
        'customer_id' => 110,
        'status'      => \app\models\Card::STATUS_FAKE,
        'hot_keys' =>  ['no_reply' => 3, 'no_signal' => 1, 'disconnect' => 4, 'voice_mail' => 1, 'declined_603' => 1],
    ],
    /**
     * @see NextCustomerServiceTest::testGetNextCustomerDataForUser()
     */
    [
        'id'          => 111,
        'created'     => '2018-06-01 10:10:10',
        'updated'     => '2016-06-01 11:11:11',
        'user_id'     => 3,
        'customer_id' => 111,
        'status'      => \app\models\Card::STATUS_NOT_ON_PHONE,
        'recall_date'      => null,
    ],
    /**
     * @see NextCustomerServiceTest::testGetNextCustomerDataForUser()
     */
    [
        'id'          => 112,
        'created'     => '2018-06-01 10:10:10',
        'updated'     => '2016-06-01 11:11:11',
        'user_id'     => 4,
        'customer_id' => 112,
        'status'      => \app\models\Card::STATUS_IN_PROGRESS,
        'recall_date'      => '2018-07-01 11:11:11',
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testGetQueryForContextNexCard
     */
    [
        'id'          => 113,
        'created'     => '2018-06-01 10:10:10',
        'updated'     => '2016-06-01 11:11:11',
        'user_id'     => 4,
        'customer_id' => 113,
        'status'      => \app\models\Card::STATUS_IN_PROGRESS,
        'recall_date'      => '2018-06-01 11:11:11',
    ],
    [
        'id'          => 116,
        'created'     => '2018-06-01 10:10:10',
        'updated'     => '2016-06-01 11:11:11',
        'user_id'     => 3,
        'customer_id' => 116,
        'status'      => \app\models\Card::STATUS_NOT_ON_PHONE,
        'recall_date'      => '2019-05-27 11:11:11',
    ],

];
