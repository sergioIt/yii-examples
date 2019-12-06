<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 02.10.2018, 13:39
 */

namespace tests\fixtures;

use app\models\CardChanges;
use yii\test\ActiveFixture;

/**
 * Class CardChangesFixture
 * @package tests\fixtures
 */
class CardChangesFixture extends ActiveFixture
{
    public $modelClass = CardChanges::class;
    public $dataFile = __DIR__ . '/data/cards_changes.php';
    public $depends = [
        CardsFixture::class,
        SupportsFixture::class,
    ];
}
