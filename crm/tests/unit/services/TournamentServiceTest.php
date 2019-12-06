<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 16.05.19
 * Time: 12:15
 */

namespace app\tests\unit\services;


use app\exceptions\TournamentServiceException;
use app\models\crm\Tournament;
use app\models\crm\TournamentAccount;
use app\models\Customers;
use app\services\TournamentService;
use tests\fixtures\CustomersFixture;
use tests\fixtures\TournamentAccountFixture;
use tests\fixtures\TournamentFixture;
use tests\unit\BaseUnit;
use yii\base\InvalidArgumentException;

/**
 * Class TournamentServiceTest
 * @package app\tests\unit\services
 */
class TournamentServiceTest extends BaseUnit
{
    /**
     * @var TournamentService
     */
    protected $service;

    /* @inheritdoc */
    protected function setUp()
    {
        parent::setUp();
        $this->service = new TournamentService();
    }

    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'customers' => CustomersFixture::class,
            'tournaments' => TournamentFixture::class,
            'tournament_accounts' => TournamentAccountFixture::class,
        ];
    }

    /**
     * @dataProvider updateDataProvider
     * @param $i
     * @param $data
     */
    public function testUpdateTournament($i, $data)
    {

        switch ($i) {
            case 0: // validate exception
                $this->expectException(InvalidArgumentException::class);
                $this->expectExceptionMessage('Key `type` is required and must be not empty string');
                $this->service->updateTournament($data);

                break;

            case 1: // validate exception
                $this->expectException(InvalidArgumentException::class);
                $this->expectExceptionMessage('Key `id` is required and must be integer');
                $this->service->updateTournament($data);

                break;

            case 2: // validate exception
                $this->expectException(InvalidArgumentException::class);
                $this->expectExceptionMessage('Key `type` is required and must be not empty string');
                $this->service->updateTournament($data);

                break;

            case 3: // успешный апдейт с созданием новой записи

                $this->assertNull($tournament = Tournament::findOne($data['id']));

                $result = $this->service->updateTournament($data);

                $this->assertCount(2, $result);

                $this->assertNotNull($tournament = Tournament::findOne($data['id']));

                $this->assertEquals($data['id'], $tournament->id);
                $this->assertEquals($data['type'], $tournament->type);

                break;

            case 4: // успешный апдейт существубщей записи - меняется тип турнира с free на paid
                // (вообще, предполагаются только новые записи, но апдейты в сервисе тоже поддерживаются)

                $this->assertNotNull($tournament = Tournament::findOne($data['id']));
                $this->assertEquals('free', $tournament->type);

                $result = $this->service->updateTournament($data);

                $this->assertCount(1, $result);

                $tournament->refresh();

                $this->assertEquals($data['type'], $tournament->type);

                break;
        }
    }

    /**
     * @dataProvider updateAccountDataProvider
     * @param $i
     * @param $data
     */
    public function testUpdateTournamentAccount($i, $data)
    {
        switch ($i) {
            case 0: // validate exception: не хватает ключа id
                $this->expectException(InvalidArgumentException::class);
                $this->expectExceptionMessage('Key `id` is required and must be integer');
                $this->service->updateTournamentAccount($data);

                break;
            case 1: // validate exception: не хватает ключа tournament_id
                $this->expectException(InvalidArgumentException::class);
                $this->expectExceptionMessage('Key `tournament_id` is required and must be integer');
                $this->service->updateTournamentAccount($data);

                break;

            case 2: // validate exception: не хватает ключа user_id
                $this->expectException(InvalidArgumentException::class);
                $this->expectExceptionMessage('Key `user_id` is required and must be integer');
                $this->service->updateTournamentAccount($data);

                break;

            case 3: //не найден турнир, соответствущий аккаунту - возвращается пустой массив

                $data = $this->service->convertAttributes($data);
                $this->assertEquals([],$this->service->updateTournamentAccount($data));

                $this->assertNull(TournamentAccount::findOne($data['id']));
                $this->assertNull(Tournament::findOne($data['tournament_id']));

                break;

            case 4: // validate exception: не найден клиент - возвращается пустой массив

                $data = $this->service->convertAttributes($data);
                $this->service->updateTournamentAccount($data);

                $this->assertNull(TournamentAccount::findOne($data['id']));
                $this->assertNull(Customers::findOne($data['customer_id']));

                break;

            case 5: // успешная синхронизация: добавление аккаунта

                $this->assertNull(TournamentAccount::findOne($data['id']));

                $data = $this->service->convertAttributes($data);

                $result =  $this->service->updateTournamentAccount($data);

                $this->assertCount(3, $result);

                $this->assertNotNull($acc = TournamentAccount::findOne($data['id']));

                $this->assertEquals($data['customer_id'], $acc->customer_id);
                $this->assertEquals($data['tournament_id'], $acc->tournament_id);

                break;

            case 6: // успешная синхронизация: добавление аккаунта, на входе данные, не нуждающиеся в конвертации,
                // но всё долно пройти так же как в case #5

                $this->assertNull(TournamentAccount::findOne($data['id']));

                $data = $this->service->convertAttributes($data);

                $result =  $this->service->updateTournamentAccount($data);

                $this->assertCount(3, $result);

                $this->assertNotNull($acc = TournamentAccount::findOne($data['id']));

                $this->assertEquals($data['customer_id'], $acc->customer_id);
                $this->assertEquals($data['tournament_id'], $acc->tournament_id);

                break;
        }

    }

    /**
     * @dataProvider attributesDataProvider
     * @param array $data
     */
    public function testConvertAttributes($data)
    {
        $result = $this->service->convertAttributes($data);

        $this->assertCount(2, $result);

        $this->assertTrue(array_key_exists('id', $result));
        $this->assertTrue(array_key_exists('customer_id', $result));

        $this->assertFalse(array_key_exists('user_id', $result));
    }

    /**
     * @return array
     */
    public function updateDataProvider(): array
    {
        return [
            [
                0,
                [
                    'id' => 1096,
                ],
            ],
            [
                1,
                [
                    'date_time' => '2019-05-15 12:00:32',
                ],
            ],
            [
                2,
                [
                    'id' => 1096,
                    'type' => '',
                ],
            ],
            [
                3,
                [
                    'id' => 1096,
                    'type' => 'free',
                ],
            ],
            [
                4,
                [
                    'id' => 1,
                    'type' => 'paid',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function updateAccountDataProvider(): array
    {
        return [
            [
                0,
                [
                    'www' => 1096,
                ],
            ],
            [
                1,
                [
                    'id' => 1001,
                    'date_time' => '2019-05-15 12:00:32',
                ],
            ],
            [
                2,
                [
                    'id' => 1001,
                    'tournament_id' => 1089,
                ],
            ],
            [
                3,
                [
                    'id' => 1096,
                    'tournament_id' => 3,
                    'user_id' => 222,
                ],
            ],
            [
                4,
                [
                    'id' => 1096,
                    'tournament_id' => 1,
                    'user_id' => 1,
                ],
            ],

            [
                5,
                [
                    'id' => 1096,
                    'tournament_id' => 1,
                    'user_id' => 101,
                ],
            ],

            [
                5,
                [
                    'id' => 1096,
                    'tournament_id' => 1,
                    'user_id' => 101,
                ],
            ],
            [
                6,
                [
                    'id' => 1096,
                    'tournament_id' => 1,
                    'customer_id' => 107,
                ],
            ],

        ];
    }

    /**
     * @return array
     */
    public function attributesDataProvider(): array
    {
        return [
            [
                [
                    'id'        => 3,
                    'user_id' => 101,
                ],
            ],
        ];
    }
}
