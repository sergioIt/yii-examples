<?php

namespace tests\fixtures;

use yii\test\ActiveFixture;

class CallsIncomingDataFixture extends ActiveFixture
{
    public $modelClass = 'app\models\CallsIncomingData';
    public $dataFile = '@tests/fixtures/data/calls_incoming_data.php';
}
