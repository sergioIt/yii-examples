<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 24.04.19
 * Time: 13:32
 */

namespace app\tests\fixtures;


use yii\test\ActiveFixture;

/**
 * Class DeclineReasonFixture
 * @package app\tests\fixtures
 */
class DeclineReasonFixture extends ActiveFixture
{
    public $modelClass = 'app\models\DeclineReason';
    public $dataFile = '@tests/fixtures/data/decline_reason.php';
}
