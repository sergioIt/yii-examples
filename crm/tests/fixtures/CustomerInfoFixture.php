<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 04.06.19
 * Time: 13:43
 */

namespace app\tests\fixtures;


use yii\test\ActiveFixture;

/**
 * Class CustomerInfoFixture
 * @package app\tests\fixtures
 */
class CustomerInfoFixture extends ActiveFixture
{
    public $db = 'db2';
    public $modelClass = 'app\models\CustomerInfo';
    public $dataFile = __DIR__.'/data/customer_info.php';
}
