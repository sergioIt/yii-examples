<?php

return [
    [
        'id'      => 101,
        'created' => '2018-05-01 16:59:26.218493',
        'card_id' => 102,
        'user_id' => 2,
        'type'    => \app\models\CardChanges::TYPE_STATUS_CHANGE,
        'status'  => \app\models\Card::STATUS_APPROVE,
    ],
    [
        'id'      => 102,
        'created' => '2018-05-01 16:59:26.218493',
        'card_id' => 103,
        'user_id' => 2,
        'type'    => \app\models\CardChanges::TYPE_STATUS_CHANGE,
        'status'  => \app\models\Card::STATUS_IN_PROGRESS,
    ],
];
