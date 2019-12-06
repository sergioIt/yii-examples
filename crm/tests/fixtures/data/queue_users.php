<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 26.03.19
 * Time: 16:14
 */
return [
    [
        'id' => 1,
        'updated' => '2018-10-25 22:53:10',
        'user_id' => 2,
        'queue_id' => 1,
        'sort' => 2,
        'enabled' => 1,
    ],
    [
        'id' => 2,
        'updated' => '2018-10-25 22:53:10',
        'user_id' => 2,
        'queue_id' => 2,
        'sort' => 1,
        'enabled' => 1,
    ],
    [
        'id' => 3,
        'updated' => '2018-10-25 22:53:10',
        'user_id' => 2,
        'queue_id' => 3,
        'sort' => 3,
        'enabled' => 1,
    ],
    [
        'id' => 4,
        'updated' => '2018-10-25 22:53:10',
        'user_id' => 2,
        'queue_id' => 4,
        'sort' => 4,
        'enabled' => 1,
    ],
    [
        'id' => 5,
        'updated' => '2018-10-25 22:53:10',
        'user_id' => 2,
        'queue_id' => 5,
        'sort' => 5,
        'enabled' => 1,
    ],
    [
        'id' => 6,
        'updated' => '2018-10-25 22:53:10',
        'user_id' => 2,
        'queue_id' => 6,
        'sort' => 6,
        'enabled' => 1,
    ],
    [
        'id' => 7,
        'updated' => '2018-10-25 22:53:10',
        'user_id' => 2,
        'queue_id' => 7,
        'sort' => 7,
        'enabled' => 1,
    ],

    /**
     * для user_id =3 имитируем получение карточки из очререди demo_nop_common
     *  @see NextCustomerServiceTest::testGetNextCustomerDataForUser()
     */
    [
        'id' => 8,
        'updated' => '2018-10-25 22:53:10',
        'user_id' => 3,
        'queue_id' => 8,
        'sort' => 1,
        'enabled' => 1,
    ],
    [
        'id' => 9,
        'updated' => '2018-10-25 22:53:10',
        'user_id' => 3,
        'queue_id' => 1,
        'sort' => 2,
        'enabled' => 1,
    ],
    /**
     * для user_id = 4 имитируем получение карточки из очререди demo_in_app_recall (частной)
     *  @see NextCustomerServiceTest::testGetNextCustomerDataForUser()
     */
    [
        'id' => 10,
        'updated' => '2018-10-25 22:53:10',
        'user_id' => 4,
        'queue_id' => 3,
        'sort' => 1,
        'enabled' => 0,
    ],
    // эта очередь должна сработать
    [
        'id' => 11,
        'updated' => '2018-10-25 22:53:10',
        'user_id' => 4,
        'queue_id' => 2,
        'sort' => 1,
        'enabled' => 1,
    ],
    [
        'id' => 12,
        'updated' => '2018-10-25 22:53:10',
        'user_id' => 4,
        'queue_id' => 1,
        'sort' => 2,
        'enabled' => 0,
    ],

    /**
     * для user_id = 6 имитируем получение карточки из очререди demo_in_app_recall (общей)
     *  @see NextCustomerServiceTest::testGetNextCustomerDataForUser()
     */

    [
        'id' => 13,
        'updated' => '2018-10-25 22:53:10',
        'user_id' => 6,
        'queue_id' => 2,
        'sort' => 1,
        'enabled' => 1,
    ],
    [
        'id' => 14,
        'updated' => '2018-10-25 22:53:10',
        'user_id' => 6,
        'queue_id' => 1,
        'sort' => 2,
        'enabled' => 0,
    ],
    /**
     * для user_id = 8 имитируем только выклюенные очереди - должно сработать исключение
     *  @see NextCustomerServiceTest::testGetNextCustomerDataForUserExceptions()
     */
    [
        'id' => 15,
        'updated' => '2018-10-25 22:53:10',
        'user_id' => 8,
        'queue_id' => 2,
        'sort' => 1,
        'enabled' => 0,
    ],
    [
        'id' => 16,
        'updated' => '2018-10-25 22:53:10',
        'user_id' => 8,
        'queue_id' => 4,
        'sort' => 2,
        'enabled' => 0,
    ],
];
