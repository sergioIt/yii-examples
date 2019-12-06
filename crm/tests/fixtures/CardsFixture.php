<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 21.05.2018, 15:33
 */

namespace tests\fixtures;

use app\models\Card;
use yii\test\ActiveFixture;

/**
 * Class CardsFixture
 * @package tests\fixtures
 */
class CardsFixture extends ActiveFixture
{
    public $modelClass = Card::class;
    public $dataFile = __DIR__ . '/data/cards.php';
    public $depends = [
        SupportsFixture::class,
        CustomerFixture::class,
    ];
}
