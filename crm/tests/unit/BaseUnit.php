<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 27.04.2018, 18:07
 */

namespace tests\unit;

use app\models\Support;
use Codeception\Test\Unit;
use tests\fixtures\SupportsFixture;
use yii\test\FixtureTrait;

/** Base Unit class */
class BaseUnit extends Unit
{
    use FixtureTrait;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->initFixtures();

        # Авторизация
        $this->assertNotNull($authUser = Support::findOne(99));
        $this->assertTrue(\Yii::$app->user->login($authUser));
    }

    /**
     * Фикстуры, подгружаемые для всех тестов обязательно
     * @return array
     */
    public function globalFixtures(): array
    {
        return [
            'supports' => SupportsFixture::class,
        ];
    }
}
