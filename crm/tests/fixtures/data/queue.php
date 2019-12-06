<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25.03.19
 * Time: 18:42
 */
return [
    [
        'id' => 1,
        'created' => '2018-10-25 22:53:10',
        'description' => 'new demo customers sorted by date and discriminant',
        'short_name' => 'demo_common',
        'enabled' => true,
    ],

    [
        'id' => 2,
        'created' => '2018-10-25 22:53:10',
        'description' => 'карточки, закрепленные за конкретным селлером в статусе In Progress и Deposit (оба mode DEMO), у которых подошло время перезвона',
        'short_name' => 'demo_ip_app_recall',
        'enabled' => true,
    ],
    [
        'id' => 4,
        'created' => '2018-10-25 22:53:10',
        'description' => 'in progress (demo), Deposit(demo), NOP (demo) без даты перезвона',
        'short_name' => 'demo_ip_app_empty_recall',
        'enabled' => true,
    ],
    [
        'id' => 5,
        'created' => '2018-10-25 22:53:10',
        'description' => 'карточки REAL, у которых последние риал и демо сделки были сделаны более 170 часов назад, а текущий баланс более 10% от последнего депозита. Для частной очереди - закрепленные за конкретным селлером, для общей - карточки уволенных селлеров по выше перечисленным параметрам',
        'short_name' => 'real_stopped',
        'enabled' => true,
    ],
    [
        'id' => 6,
        'created' => '2018-10-25 22:53:10',
        'description' => 'карточки REAL, у которых последняя демо сделка была сделана более 110 часов назад при отстутсвии за это время реальных сделок, а текущий баланс менее 10% от последнего депозита. Для частной очереди - закрепленные за конкретным селлером, для общей - карточки уволенных селлеров по выше перечисленным параметрам',
        'short_name' => 'real_inactive',
        'enabled' => true,
    ],
    [
        'id' => 8,
        'created' => '2018-10-25 22:53:10',
        'description' => 'карточки в статусе Demo и not on phone, которые принадлежат селлерам (работающим и уволенным, или робоколу). сортировка по времени перезвона',
        'short_name' => 'demo_nop_common',
        'enabled' => true,
    ],
];
