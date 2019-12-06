<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 21.05.2018, 14:57
 */

namespace tests\services;

use app\models\AffectedCustomers;
use app\models\Payments;
use app\services\BonusService;
use app\services\PaymentsBonusService;
use tests\unit\BaseUnit;
use tests\fixtures\AffectedCustomersFixture;
use tests\fixtures\CardsFixture;
use tests\fixtures\LogTransitsManagerFixture;
use tests\fixtures\PaymentsFixture;
use yii\db\Query;

/**
 * Class BonusServiceTest
 * @package tests\services
 */
class BonusServiceTest extends BaseUnit
{
    /**
     * @var BonusService
     */
    protected $service;

    /* @inheritdoc */
    protected function setUp()
    {
        parent::setUp();
        $this->service = new BonusService();

        # перед тем, как доставать из таблицы бонусы, их нужно просчитать и записать
        foreach (AffectedCustomers::find()->select(['customer_id'])->asArray()->column() as $customerId) {
            PaymentsBonusService::update($customerId);
        }
    }

    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'cards'              => CardsFixture::class,
            'payments'           => PaymentsFixture::class,
            'affectedCustomers'  => AffectedCustomersFixture::class,
            'logTransitsManager' => LogTransitsManagerFixture::class,
        ];
    }

    /**
     * Кейс:
     *
     * Customer #101
     * Affect:          2018-06-02 19:00:00
     * First deposit:   2018-07-02 00:00:00 (> 72h, type = 2)
     * Reset:           2018-07-02 19:00:00 (type = 3)
     * Transit 2 => 3   2018-07-03 15:00:00
     * Transit 3 => 2   2018-07-04 15:00:00
     */
    public function testGetBonusPayments()
    {
        # запрос, просчитывающий все на ходу
        $query = $this->service->getCalcBonusPaymentsQuery();
        $query->andWhere(['owners.owner_id' => 2]);

        $this->paymentsQueryTest($query);

        # запрос на основе таблицы payments_bonus
        $query = $this->service->getBonusPaymentsQuery();
        $query->andWhere(['pb.support_id' => 2]);

        $this->paymentsQueryTest($query);
    }

    /**
     * Так как запроса два - один просчитывает все на ходу (его берем за эталон),
     * и второй - из обновляемой таблицы, результаты должны быть одинаковые -
     *  оба запроса прогоним через этот метод
     * @param Query $query
     */
    protected function paymentsQueryTest(Query $query)
    {
        $paymentsSum = (new Query())->select([
            'amount' => 'SUM(amount)',
            'currency',
        ])->from(['r' => $query])
            ->orderBy(['currency' => SORT_ASC]) // для уточнения порядка ожидаемого массива
            ->groupBy(['currency'])->all();

        $this->assertEquals([
            ['amount' => 290.00, 'currency' => 'PHP'],
            ['amount' => 2100.00, 'currency' => 'VND'],
        ], $paymentsSum);

        $payments = $query->orderBy(['deposit' => SORT_ASC])->all();

        $this->assertCount(4, $payments);

        foreach ($payments as $key => $payment) {

            $this->assertEquals(Payments::STATUS_APPROVED, $payment['status']);
            $this->assertEquals('2018-06-02 19:00:00', $payment['affected']);
            $this->assertEquals('2018-07-02 19:00:00', $payment['reset']);

            switch ($key) {
                case 0:
                    $this->assertEquals(4, $payment['id']);
                    $this->assertEquals('2018-07-02 00:00:00', $payment['deposit']);
                    $this->assertEquals($this->service::TYPE_BEFORE_FIRST_DEPOSIT_OUT_PERIOD, $payment['type']);
                    $this->assertEquals('VND', $payment['currency']);
                    break;

                case 1:
                    $this->assertEquals(5, $payment['id']);
                    $this->assertEquals('2018-07-03 00:00:00', $payment['deposit']);
                    $this->assertEquals($this->service::TYPE_AFTER_FIRST_DEPOSIT, $payment['type']);
                    $this->assertEquals('VND', $payment['currency']);
                    break;

                case 2:
                    $this->assertEquals(9, $payment['id']);
                    $this->assertEquals('2018-07-11 05:00:00', $payment['deposit']);
                    $this->assertEquals($this->service::TYPE_AFTER_FIRST_DEPOSIT, $payment['type']);
                    $this->assertEquals('PHP', $payment['currency']);
                    break;

                case 3:
                    $this->assertEquals(10, $payment['id']);
                    $this->assertEquals('2018-07-22 05:00:00', $payment['deposit']);
                    $this->assertEquals($this->service::TYPE_AFTER_FIRST_DEPOSIT, $payment['type']);
                    $this->assertEquals('PHP', $payment['currency']);
                    break;

                default: // сюда не должны попасть
                    $this->assertTrue(false);
            }
        }
    }



    /**
     * Test calc bonus by amount and type
     */
    public function testCalcBonus()
    {
        $this->assertEquals(2, $this->service::calcBonusByType(100, $this->service::TYPE_BEFORE_FIRST_DEPOSIT_IN_PERIOD));
        $this->assertEquals(4, $this->service::calcBonusByType(200, $this->service::TYPE_BEFORE_FIRST_DEPOSIT_IN_PERIOD));

        $this->assertEquals(0, $this->service::calcBonusByType(100, $this->service::TYPE_BEFORE_FIRST_DEPOSIT_OUT_PERIOD));
        $this->assertEquals(0, $this->service::calcBonusByType(500, $this->service::TYPE_BEFORE_FIRST_DEPOSIT_OUT_PERIOD));

        $this->assertEquals(0.5, $this->service::calcBonusByType(100, $this->service::TYPE_AFTER_FIRST_DEPOSIT));
        $this->assertEquals(0.25, $this->service::calcBonusByType(50, $this->service::TYPE_AFTER_FIRST_DEPOSIT));
    }

    /**
     * Test get customer bonus info
     */
    public function testGetCustomerBonusInfo()
    {
        $customerId = 101;
        $bonusInfo = $this->service->getCustomerBonusInfoQuery($customerId)->one();

        $this->assertArrayHasKey('affected', $bonusInfo);
        $this->assertArrayHasKey('reset', $bonusInfo);
        $this->assertArrayHasKey('first_deposit_date', $bonusInfo);
        $this->assertArrayHasKey('last_deposit_date', $bonusInfo);
        $this->assertArrayHasKey('affected_type', $bonusInfo);
        $this->assertArrayHasKey('reset_type', $bonusInfo);

        $this->assertEquals('2018-06-02 19:00:00', $bonusInfo['affected']);
        $this->assertEquals('2018-07-02 19:00:00', $bonusInfo['reset']);
        $this->assertEquals('2018-07-02 00:00:00', $bonusInfo['first_deposit_date']);
        $this->assertEquals('2018-07-22 05:00:00', $bonusInfo['last_deposit_date']);
        $this->assertEquals(2, $bonusInfo['affected_type']);
        $this->assertEquals(3, $bonusInfo['reset_type']);

        $customerBonus = $this->service->getCustomerBonus($customerId);

        $this->assertEquals($customerBonus->affected, $bonusInfo['affected']);
        $this->assertEquals($customerBonus->reset, $bonusInfo['reset']);
        $this->assertEquals($customerBonus->first_deposit_date, $bonusInfo['first_deposit_date']);
        $this->assertEquals($customerBonus->last_deposit_date, $bonusInfo['last_deposit_date']);
        $this->assertEquals($customerBonus->affected_type, $bonusInfo['affected_type']);
        $this->assertEquals($customerBonus->reset_type, $bonusInfo['reset_type']);
        $this->assertEquals($customerBonus->affection_left, $bonusInfo['affection_left']);

        $this->assertTrue($customerBonus->validate());
    }



    /**
     * @return array [i, affectType, resetType]
     */
    public function typeDataProvider(): array
    {
        return [
            [0, 1, null],
            [1, 2, 3],
            [2, 3, 2],
            [3, 4, 1],
            [4, 3],
        ];
    }

    /**
     * Calculate sum to pay for seller
     *
     * @dataProvider bonusSumDataProvider
     *
     * @param $expectedPaySum
     * @param $expectedDiff
     * @param $unpaidBonusSum
     * @param $beforeLastPayBonusSum
     * @param $paid
     */
    public function testGetPaySum($expectedPaySum, $expectedDiff, $unpaidBonusSum, $beforeLastPayBonusSum, $paid)
    {
        $this->assertEquals($expectedDiff, BonusService::getDiff($beforeLastPayBonusSum, $paid));
        $this->assertEquals($expectedPaySum, BonusService::getPaySum($unpaidBonusSum, $beforeLastPayBonusSum, $paid));
    }

    /**
     * @return array
     */
    public function bonusSumDataProvider(): array
    {
        return [
            [150.44, 0, 150.44, null, null],
            [160.25, 10.25, 150, 100.25, 90],
            [193.73, 43.73, 150, 43.73, null],
            [100, -200, 300, 100, 300],
            [0, -140, 140, 300, 440], // 140 + (300 - 440) = 140 - 140 = 0
            [0, -184.35, 55, 15.65, 200], // 55 + (15.65 - 200) = 55 - 184.35 ... < 0 ... = 0
        ];
    }
}
