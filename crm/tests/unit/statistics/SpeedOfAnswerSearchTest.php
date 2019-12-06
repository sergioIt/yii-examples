<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 18.06.19
 * Time: 16:52
 */

namespace app\tests\unit\statistics;


use app\models\statistics\SpeedOfAnswerSearch;
use app\tests\fixtures\CallsFixture;
use tests\unit\BaseUnit;
use yii\data\SqlDataProvider;

/**
 * Class SpeedOfAnswerSearchTest
 * @package app\tests\unit\statistics
 */
class SpeedOfAnswerSearchTest extends BaseUnit
{

    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'calls'    => CallsFixture::class,
        ];
    }

    /**
     * тест на то, что поиск не падает и выдёат не пустые данные
     */
    public function testSearch(){

        $searchModel = new SpeedOfAnswerSearch();
        $dataProvider = $searchModel->search([]);

        $this->assertInstanceOf(SqlDataProvider::class, $dataProvider);

        $this->assertTrue($dataProvider->getCount() > 0);
    }
}
