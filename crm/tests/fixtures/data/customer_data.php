<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 15.05.19
 * Time: 13:11
 */
return [
    [
        // новые записи по-умолчанию начинаются с единицы, а эта запись нужна для проверки апдейта существубщей записи,
        // поэтому выбран достаточно большой id и не было конфликтов с записями, создающимися в ходе тестов
        'id' => 100,
        'customer_id' => 766555,
        'last_tournament_operation' => 1557937357,
        'last_refill_date' => '2019-05-15 08:24:35',
        'last_fillup_date' => '2019-05-15 08:24:35',
    ],
    /**
     * @see \app\tests\unit\services\CustomerQueryServiceTest::testDemoNoTournamentsQueryResult()
     */
    [
        'id' => 101,
        'customer_id' => 120,
        'last_tournament_operation' => 1557937357,
        'last_refill_date' => '2019-05-15 08:24:35',
//        'last_fillup_date' => '2019-05-15 08:24:35',
    ],
];
