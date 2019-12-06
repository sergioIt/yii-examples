<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 15.05.19
 * Time: 13:10
 */

namespace app\tests\fixtures;


use yii\test\ActiveFixture;

/**
 * Class CustomerDataFixture
 * @package app\tests\fixtures
 */
class CustomerDataFixture extends ActiveFixture
{
    public $modelClass = 'app\models\CustomersData';
    public $dataFile =__DIR__ . '/data/customer_data.php';
}
