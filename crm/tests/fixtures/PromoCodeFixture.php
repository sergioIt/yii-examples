<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 20.08.19
 * Time: 17:19
 */

namespace tests\fixtures;

use yii\test\ActiveFixture;

class PromoCodeFixture extends ActiveFixture
{
    public $db= 'db2';
    public $modelClass = 'app\models\trade\PromoCode';
    public $dataFile = __DIR__ . '/data/promo_code.php';
}
