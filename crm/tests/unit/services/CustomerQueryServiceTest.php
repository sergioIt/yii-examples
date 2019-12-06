<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 20.03.19
 * Time: 18:10
 */

namespace app\tests\unit\services;


use app\exceptions\CustomerQueryServiceException;
use app\helpers\param\CurrencyParam;
use app\models\Customer;
use app\models\Customers;
use app\models\InitialDeposits;
use app\models\Payments;
use app\models\Support;
use app\services\CustomerQueryService;
use app\tests\fixtures\CustomerDataFixture;
use app\tests\fixtures\CustomerInfoFixture;
use tests\fixtures\CardsFixture;
use tests\fixtures\CountryFixture;
use tests\fixtures\CustomerFixture;
use tests\fixtures\CustomersFixture;
use tests\fixtures\InitialDepositsFixture;
use tests\fixtures\PaymentsFixture;
use tests\fixtures\TournamentAccountFixture;
use tests\fixtures\TournamentFixture;
use tests\unit\BaseUnit;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class CustomerQueryService
 * @package app\tests\unit\services
 */
class CustomerQueryServiceTest extends BaseUnit
{

    /**
     * @var CustomerQueryService
     */
    protected $service;

    /* @inheritdoc */
    protected function setUp()
    {
        parent::setUp();
        $this->service = new CustomerQueryService();
    }

    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'country' => CountryFixture::class,
            'customers_synced' => CustomersFixture::class,
            'customers' => CustomerFixture::class,
            'payments' => PaymentsFixture::class,
            'cards' => CardsFixture::class,
            'customers_data' => CustomerDataFixture::class,
            'tournaments' => TournamentFixture::class,
            'tournaments_accounts' => TournamentAccountFixture::class,
            'init_deposits' => InitialDepositsFixture::class,
            'customer_info' => CustomerInfoFixture::class,
        ];
    }

    /**
     * базовая проверка всех запросов на то, что они выполняются без ошибок
     */
    public function testQueriesByTypes()
    {

        $types = $this->service->getAllTypes();

        foreach ($types as $type) {

            if ($type === 'demo_no_refill') {

                $query = $this->service->getDemoNoRefillQuery(100);

                $this->assertInstanceOf(ActiveQuery::class, $query);

                $this->assertTrue(is_array($query->all()));

                continue;
            }

            $query = $this->service->getQueryByType($type);

            $this->assertInstanceOf(ActiveQuery::class, $query);

            $this->assertTrue(is_array($query->all()));
        }
    }

    /**
     *  проверяет, что данные запроса в demo_common идёт в ожидаемом порядке (по дате регистрации и дискримимнанту)
     */
    public function testDemoCommonResult(){

        $query = $this->service->getDemoCommonQuery();

        $data = $query->asArray()->all();

        $this->assertArrayHasKey(0, $data);

        $item = $data[0];

        $this->assertArrayHasKey('reg_date', $item);
        $this->assertArrayHasKey('discriminant', $item);
        $this->assertArrayHasKey('row_num', $item);
        $this->assertArrayHasKey('phone', $item);
        $this->assertArrayHasKey('id', $item);

        $this->assertEquals($item['reg_date'],'2018-03-06');
        $this->assertEquals($item['discriminant'],'3.20000000');
        $this->assertEquals($item['row_num'], 1);
        $this->assertEquals($item['id'], 109);

        $this->assertArrayHasKey(1, $data);

        $item = $data[1];

        $this->assertArrayHasKey('reg_date', $item);
        $this->assertArrayHasKey('discriminant', $item);
        $this->assertArrayHasKey('row_num', $item);
        $this->assertArrayHasKey('phone', $item);
        $this->assertArrayHasKey('id', $item);

        $this->assertEquals($item['reg_date'],'2018-03-06');
        $this->assertEquals($item['discriminant'],'2.70000000');
        $this->assertEquals($item['row_num'], 2);
        $this->assertEquals($item['id'], 114);

        $this->assertArrayHasKey(2, $data);

        $item = $data[2];

        $this->assertArrayHasKey('reg_date', $item);
        $this->assertArrayHasKey('discriminant', $item);
        $this->assertArrayHasKey('row_num', $item);
        $this->assertArrayHasKey('phone', $item);
        $this->assertArrayHasKey('id', $item);

        $this->assertEquals($item['reg_date'],'2018-03-05');
        $this->assertNull($item['discriminant']);
        $this->assertEquals($item['row_num'], 1);
        $this->assertEquals($item['id'], 112);

    }

    /**
     *
     */
    public function testDemoBaseQueryResult(){

        $q = $this->service->getDemoBaseQuery();

        $data = $q->asArray()->indexBy('id')->column();

        $this->assertArrayHasKey(109, $data);
        $this->assertArrayHasKey(114, $data);
        $this->assertArrayHasKey(112, $data);

        $this->assertNotNull($customer = Customer::findOne(116));

        $this->assertNotNull($customer->card_id);
        //  в выборку не должна попасть запись с непустой card_id
        $this->assertArrayNotHasKey(116, $data);

        $this->assertNotNull($customer = Customer::findOne(117));

        $this->assertTrue($customer->processing_card);

        //  в выборку не должна попасть запись с processing_card = true
        $this->assertArrayNotHasKey(117, $data);
    }

    /**
     *
     */
    public function testDemoNoActivityOneDayResult()
    {

        $customer = Customers::findOne(117);

        // модифицирем дату регистрации, чтобы она попала в интервал между 1 и 2 днями от текущего момента
        $customer->reg_date = time() - 60 * 60 * 36;

        $this->assertTrue($customer->save());
        /**
         * demo_no_activity_1day
         */
        $query = $this->service->getDemoNoActivityOneDay();
        $query->addSelect(['id']);
        $data = $query->indexBy('id')->asArray()->all();

        $this->assertArrayHasKey($customer->id, $data);
        // имитиурем наличие недавней операции
        $customer->last_operation_demo = time();
        $this->assertTrue($customer->save());

        // клиент с недавней операцией не должен попасть в выборку
        $data = $query->indexBy('id')->asArray()->all();

        $this->assertArrayNotHasKey($customer->id, $data);
    }

    public function testDemoNoActivityThreeHoursResult()
    {

        $customer = Customers::findOne(117);
        $customer->reg_date = time() - 60 * 60 * 3;
        $this->assertTrue($customer->save());

        $query = $this->service->getDemoNoActivityThreeHours();

        $data = $query->indexBy('id')->asArray()->all();

        $this->assertArrayHasKey($customer->id, $data);
        // имитиурем наличие недавней операции
        $customer->last_operation_demo = time();
        $this->assertTrue($customer->save());

        // клиент с недавней операцией не должен попасть в выборку
        $data = $query->indexBy('id')->asArray()->all();

        $this->assertArrayNotHasKey($customer->id, $data);

    }

    /**
     *
     */
    public function testDemoNoFillupQueryResult()
    {

        $customer = Customers::findOne(118);
        $customer->reg_date = time() - 60 * 60 * 36;
        $this->assertTrue($customer->save());

        $realCustomer = Customers::findOne(119);

        $realCustomer->reg_date = time() - 60 * 60 * 36;
        $this->assertTrue($realCustomer->save());

        $this->assertTrue($realCustomer->isReal());

        $query = $this->service->getDemoNoFillupQuery();

        $data = $query->indexBy('id')->asArray()->all();

        $this->assertArrayHasKey($customer->id, $data);
        # реальный клиент не должен попасть в выборку
        $this->assertArrayNotHasKey($realCustomer->id, $data);
    }

    public function testDemoNoTournamentsQueryResult()
    {

        $customer = Customers::findOne(118);
        $customer->reg_date = time() - 60 * 60 * 36;
        $this->assertTrue($customer->save());

        # реальный клиент не должен попасть в выборку

        $realCustomer = Customers::findOne(119);

        $realCustomer->reg_date = time() - 60 * 60 * 36;
        $this->assertTrue($realCustomer->save());

        $this->assertTrue($realCustomer->isReal());

        $demoCustomerWTournaments = Customers::findOne(120);

        $this->assertFalse($demoCustomerWTournaments->isReal());

        $demoCustomerWTournaments->reg_date = time() - 60 * 60 * 36;
        $this->assertTrue($demoCustomerWTournaments->save());

        $query = $this->service->getDemoNoTournamentsQuery();

        $data = $query->indexBy('id')->asArray()->all();

        $this->assertArrayHasKey($customer->id, $data);


        $this->assertArrayNotHasKey($realCustomer->id, $data);

        $this->assertArrayNotHasKey($demoCustomerWTournaments->id, $data);


    }

    public function testDemoNoRefillQueryResult()
    {

        $customerExpected = Customers::findOne(121);
        $customerExpected->reg_date = time() - 60 * 60 * 4;
        $this->assertTrue($customerExpected->save());

        $customerBigBalance = Customers::findOne(122);
        $customerBigBalance->reg_date = time() - 60 * 60 * 4;
        $this->assertTrue($customerBigBalance->save());

        $customerHasRefill = Customers::findOne(120);
        $customerHasRefill->reg_date = time() - 60 * 60 * 4;
        $this->assertTrue($customerHasRefill->save());

        $customerReal = Customers::findOne(119);
        $customerReal->reg_date = time() - 60 * 60 * 4;
        $this->assertTrue($customerReal->save());

        $initialDeposit = InitialDeposits::getByCurrency($customerExpected->currency);

        $query = $this->service->getDemoNoRefillQuery($initialDeposit);

        $this->assertInstanceOf(ActiveQuery::class, $query);

        $data = $query->indexBy('id')->asArray()->all();

        $this->assertArrayHasKey($customerExpected->id, $data);
        $this->assertArrayNotHasKey($customerBigBalance->id, $data);
        $this->assertArrayNotHasKey($customerHasRefill->id, $data);
        $this->assertArrayNotHasKey($customerReal->id, $data);

    }

    /**
     *
     */
    public function testAllNoPayTournamentsQueryResult()
    {

        $demoCustomerWFreeTournament = Customers::findOne(123);
        $demoCustomerWFreeTournament->reg_date = time() - 60 * 60 * 48;
        $this->assertTrue($demoCustomerWFreeTournament->save());

        $demoCustomerWPayTournament = Customers::findOne(124);
        $demoCustomerWPayTournament->reg_date = time() - 60 * 60 * 48;
        $this->assertTrue($demoCustomerWPayTournament->save());

        $realCustomerWFreeTournament = Customers::findOne(125);
        $realCustomerWFreeTournament->reg_date = time() - 60 * 60 * 48;
        $this->assertTrue($realCustomerWFreeTournament->save());


        $query = $this->service->getAllNoPayTournamentsQuery();

        $data = $query->indexBy('id')->asArray()->all();

        $this->assertArrayHasKey($demoCustomerWFreeTournament->id, $data);
        $this->assertArrayNotHasKey($demoCustomerWPayTournament->id, $data);
        $this->assertArrayHasKey($realCustomerWFreeTournament->id, $data);
    }

    /**
     *
     */
    public function testRecentPaymentFailedQueryResult()
    {
        // клиент, у которого есть последний успешный платёж - должен попасть в выборку
        $customerWLastFailedPayment = Customers::findOne(126);
        // клиент, у которого нет последнего успешного платёжа - не должен попасть в выборку
        $customerWLastPendingPayment = Customers::findOne(127);

        $payment = Payments::findOne(16);

        $payment->paid_date = time() - 60 * 60 * 4;

        $this->assertTrue($payment->save());

        $lastPendingPayment = Payments::findOne(17);

        $lastPendingPayment->paid_date = time() - 60 * 60 * 4;

        $this->assertTrue($lastPendingPayment->save());

        $query = $this->service->getDemoRecentPaymentFailedQuery();
        $data = $query->indexBy('id')->asArray()->all();

        $this->assertArrayHasKey($customerWLastFailedPayment->id, $data);
        $this->assertArrayNotHasKey($customerWLastPendingPayment->id, $data);
    }

    public function testGetRealStoppedQueryResult(){

        // находим клиента, у которого баланс более 10% от последнего успешного депозита
        // он не должен попасть в выборку
        $customer = Customers::findOne(128);

        $customer->last_operation_demo = time() - 60*60*180;
        $customer->last_operation_real = time() - 60*60*180;

        // находим клиента, у которого баланс менее 10% от последнего успешного депозита
        // он не должен попасть в выборку
        $customerBigBalance = Customers::findOne(129);

        $customerBigBalance->last_operation_demo = time() - 60*60*180;
        $customerBigBalance->last_operation_real = time() - 60*60*180;

        $this->assertTrue($customer->save());
        $this->assertTrue($customerBigBalance->save());

        $query = $this->service->getRealStoppedQuery();

        $data = $query->asArray()->indexBy('id')->all();

        $this->assertArrayHasKey($customer->id, $data);
        $this->assertArrayNotHasKey($customerBigBalance->id, $data);

    }

    public function testGetRealInactiveQueryResult(){

        // находим клиента, у которого баланс менее 10% от последнего успешного депозита, должен попасть в выборку
        $customerExpected = Customers::findOne(129);

        $customerExpected->last_operation_demo = time() - 60*60*220; //совершил последнюю операцию на демо-счете более чем 110 часов назад,
        // при отстутсвии за это время реальных сделок: то есть, совершил последнюю операцию на real-счете БОЛЕЕ чем 110 часов назад
        $customerExpected->last_operation_real = time() - 60*60*220;


        $this->assertTrue($customerExpected->save());

        // находим клиента,у которого баланс более 10% от последнего успешного депозита, не должен попасть в выборку
        $customerNotExpected = Customers::findOne(128);

        $customerNotExpected->last_operation_demo = time() - 60*60*220;
        $customerNotExpected->last_operation_real = time() - 60*60*220;


        $this->assertTrue($customerNotExpected->save());

        $query = $this->service->getRealInactiveQuery();

        $query->addSelect('id');
        $data = $query->asArray()->indexBy('id')->all();

        $this->assertArrayHasKey($customerExpected->id, $data);
        $this->assertArrayNotHasKey($customerNotExpected->id, $data);
    }


    public function testGetRealDemoActiveQueryResult(){

        // находим клиента, у которого баланс менее 10% от последнего успешного депозита, должен попасть в выборку
        $customerExpected = Customers::findOne(129);

        $customerExpected->last_operation_demo = time() - 60*60*80; // совершил последнюю операцию на демо-счете НЕ БОЛЕЕ чем 110 часов назад
        // но при этом на реальном счете последняя сделка более 110 часов
        $customerExpected->last_operation_real = time() - 60*60*120;

        $this->assertTrue($customerExpected->save());


        // находим клиента, у которого баланс более 10% от последнего успешного депозита, не должен попасть в выборку
        $customerNotExpected = Customers::findOne(128);

        $customerNotExpected->last_operation_demo = time() - 60*60*80;
        $customerNotExpected->last_operation_real = time() - 60*60*120;

        $this->assertTrue($customerNotExpected->save());

        $query = $this->service->getRealDemoActiveQuery();

        $data = $query->asArray()->indexBy('id')->all();

        $this->assertArrayHasKey($customerExpected->id, $data);
        $this->assertArrayNotHasKey($customerNotExpected->id, $data);

    }

    /**
     *
     */
    public function testLastPaymentQuery()
    {

        $query = $this->service->lastPaymentQuery();
        $this->assertInstanceOf(Query::class, $query);

        $this->assertTrue(is_array($query->all()));
    }

    /**
     *
     */
    public function testLastApprovedPaymentQuery()
    {

        $customerId = 111;
        $paymentIdExpected = 14;

        $query = $this->service->lastApprovedPaymentQuery()->indexBy('customer_id')->addSelect('id');
        $this->assertInstanceOf(Query::class, $query);

        $this->assertTrue(is_array($data = $query->all()));

        $this->assertArrayHasKey($customerId, $data);
        $this->assertArrayHasKey('id', $data[$customerId]);

        // тест на то, что последний успешный платёж для заданного клиента должен быть именно id=10
        $this->assertEquals($paymentIdExpected, $data[$customerId]['id']);
    }

    public function testFailedPaymentsWhichIsLastQuery()
    {

        $customerId = 111;
        $paymentIdExpected = 13;

        $query = $this->service->failedPaymentsWhichIsLastQuery();

        // добавляем это чтобы проверить запись по ожидаемому клиенту - есть ли он и есть ли там ожидаемый платёж
        $query->indexBy('customer_id');

        $this->assertInstanceOf(Query::class, $query);

        $this->assertTrue(is_array($data = $query->all()));

        $this->assertArrayHasKey($customerId, $data);
        $this->assertArrayHasKey('id', $data[$customerId]);

        //  последний платёж и при этом неуспешный для заданного клиента должен быть именно id=13
        $this->assertEquals($paymentIdExpected, $data[$customerId]['id']);
    }

    /**
     * тест на то, что запрос на последний платёж возвращает именно данные последнего по времени платежа
     */
    public function testLastPaymentForCustomer()
    {

        $customerId = 766555;

        $query = $this->service->lastPaymentQuery()->andWhere(['customer_id' => $customerId]);

        $query->select(['amount']);

        $payment = $query->one();

        $this->assertArrayHasKey('amount', $payment);

        $this->assertEquals($payment['amount'], '160.00');

        $customerId = 766556;

        $query = $this->service->lastPaymentQuery()->andWhere(['customer_id' => $customerId]);

        $query->select(['amount']);

        $payment = $query->one();

        $this->assertTrue(is_array($payment));

        $this->assertArrayHasKey('amount', $payment);

        $this->assertEquals($payment['amount'], '180.00');

    }

    /**
     *
     */
    public function testExpressions()
    {

        $this->assertInstanceOf(Expression::class, $this->service->expressionCurrentTime());
        $this->assertInstanceOf(Expression::class, $this->service->expressionPrefix());
        $this->assertInstanceOf(Expression::class, $this->service->expressionLength());
    }

    /**
     *
     */
    public function testGetDemoCardNotOnPhoneQuery()
    {

        $query = $this->service->getDemoCardNotOnPhoneQuery();

        $this->assertInstanceOf(ActiveQuery::class, $query);
        $query->addSelect('recall_date');

        $data = $query->asArray()->indexBy('id')->all();

        $this->assertTrue(is_array($data));

        // в выборку должен попасть клиент с картортокой в статусе not_on_phone и с пустым recall_date
        $this->assertArrayHasKey(111, $data);
        // в выборку должен попасть клиент с картортокой в статусе not_on_phone и с не пустым recall_date
        $this->assertArrayHasKey(116, $data);
    }


    public function testFilterConfigByContext()
    {

        $filter = CustomerQueryService::filterConfigByContext('asterisk');

        $this->assertTrue(is_array($filter));
        $this->assertTrue(count($filter) > 0);

        $filter = CustomerQueryService::filterConfigByContext('modal');

        $this->assertTrue(is_array($filter));
        $this->assertTrue(count($filter) > 0);

        $filter = CustomerQueryService::filterConfigByContext('ddd');

        $this->assertTrue(is_array($filter));
        $this->assertTrue(count($filter) === 0);


    }

    public function testGetPublicTypesForContext()
    {

        $types = CustomerQueryService::getPublicTypesForContext('asterisk');

        $this->assertTrue(is_array($types));
        $this->assertTrue(in_array('demo_common', $types));
    }

    /**
     *
     */
    public function testGetAllTypesForContext()
    {

        $filter = CustomerQueryService::getAllTypesForContext('asterisk');

        $this->assertTrue(is_array($filter));
        $this->assertTrue(count($filter) > 1);
    }

    public function testLastOperationAgeForUserQuery()
    {

        $query = $this->service->lastOperationAgeForUserQuery(766554);

        $this->assertInstanceOf(Query::class, $query);
        $this->assertTrue(is_array($query->all()));
    }

    public function testGetDefineTypeQuery()
    {

        $query = $this->service->getDefineTypeQuery(766554);

        $this->assertInstanceOf(Query::class, $query);
        $this->assertTrue(is_array($query->all()));

        $query = $this->service->getDefineTypeQuery(766555);

        $this->assertInstanceOf(Query::class, $query);
        $this->assertTrue(is_array($query->all()));
    }

    /**
     *
     */
    public function testContextIsAllowed()
    {

        $this->assertTrue(CustomerQueryService::contextIsAllowed('next_card'));
        $this->assertTrue(CustomerQueryService::contextIsAllowed('modal'));
        $this->assertTrue(CustomerQueryService::contextIsAllowed('asterisk'));
        $this->assertFalse(CustomerQueryService::contextIsAllowed('qqq'));
    }

    /**
     */
    public function testGetDemoCommonCounters()
    {

        $currencies = ['VND'];

        $counters = $this->service->getDemoCommonCounters($currencies);

        $this->assertTrue(is_array($counters));

        $this->assertArrayHasKey('VND', $counters);
        $this->assertEquals(1, $counters['VND']);
    }


    public function testGetPublicTypes()
    {


        $types = CustomerQueryService::getPublicTypes();

        $this->assertTrue(is_array($types));

        $this->assertTrue(in_array('demo_common', $types));
        $this->assertTrue(in_array('demo_nop_common', $types));
        $this->assertTrue(in_array('demo_ip_app_recall', $types));
        $this->assertTrue(in_array('demo_ip_app_empty_recall', $types));
        $this->assertTrue(in_array('real_stopped', $types));
        $this->assertTrue(in_array('real_inactive', $types));
        $this->assertTrue(in_array('real_demo_active', $types));
        $this->assertFalse(in_array('demo_active', $types));
        $this->assertFalse(in_array('real_active', $types));
        $this->assertFalse(in_array('demo_no_trades', $types));

    }

    public function testGetPrivateTypes()
    {

        $types = CustomerQueryService::getPrivateTypes();

        $this->assertTrue(is_array($types));

        $this->assertFalse(in_array('demo_common', $types));
        $this->assertFalse(in_array('demo_nop_common', $types));
        $this->assertTrue(in_array('demo_ip_app_recall', $types));
        $this->assertTrue(in_array('demo_ip_app_empty_recall', $types));
        $this->assertTrue(in_array('real_stopped', $types));
        $this->assertTrue(in_array('real_inactive', $types));
        $this->assertTrue(in_array('real_demo_active', $types));
        $this->assertFalse(in_array('demo_active', $types));
        $this->assertFalse(in_array('real_active', $types));
        $this->assertFalse(in_array('demo_no_trades', $types));
    }

    public function testGetPublicCounters()
    {

        $currencies = CurrencyParam::getAssocList();

        $counters = $this->service->getPublicCounters($currencies, CustomerQueryService::CONTEXT_NEXT_CARD);

        $this->assertTrue(is_array($counters));

        $this->assertArrayHasKey('demo_common', $counters);
        $this->assertArrayHasKey('demo_nop_common', $counters);
        $this->assertArrayHasKey('demo_ip_app_recall', $counters);
        $this->assertArrayHasKey('demo_ip_app_empty_recall', $counters);
        $this->assertArrayHasKey('real_stopped', $counters);
        $this->assertArrayHasKey('real_inactive', $counters);
        $this->assertArrayHasKey('real_demo_active', $counters);
    }

    public function testGetPrivateCounters()
    {

        $activeSellers = Support::find()->active()->anySeller()->select(['id'])->column();

        $counters = $this->service->getPrivateCounters($activeSellers, CustomerQueryService::CONTEXT_NEXT_CARD);

        $this->assertTrue(is_array($counters));

        $this->assertArrayNotHasKey('demo_common', $counters);
        $this->assertArrayNotHasKey('demo_nop_common', $counters);
        $this->assertArrayHasKey('demo_ip_app_recall', $counters);
        $this->assertArrayHasKey('demo_ip_app_empty_recall', $counters);
        $this->assertArrayHasKey('real_stopped', $counters);
        $this->assertArrayHasKey('real_inactive', $counters);
        $this->assertArrayHasKey('real_demo_active', $counters);

    }

    public function testFilterPrivateCountersByUser()
    {

        $activeSellers = Support::find()->active()->anySeller()->select(['id'])->column();

        $counters = $this->service->getPrivateCounters($activeSellers, CustomerQueryService::CONTEXT_NEXT_CARD);

        $filtered = CustomerQueryService::filterPrivateCountersByUser($counters, 4);

        $this->assertTrue(is_array($filtered));
    }

    public function testFilterPublicCountersByCurrencies()
    {

        $currencies = CurrencyParam::getAssocList();

        $publicCounters = $this->service->getPublicCounters($currencies, CustomerQueryService::CONTEXT_NEXT_CARD);

        $userCurrencies = Support::findOne(2)->getCurrencies();

        $filtered = CustomerQueryService::filterPublicCountersByCurrencies($publicCounters, $userCurrencies);

        $this->assertTrue(is_array($filtered));
    }

    public function testComposeCountersAndLabels()
    {

        $userId = 2;

        $currencies = CurrencyParam::getAssocList();

        $publicCounters = $this->service->getPublicCounters($currencies, CustomerQueryService::CONTEXT_NEXT_CARD);

        $userCurrencies = Support::findOne(2)->getCurrencies();

        $publicCountersForUser = CustomerQueryService::filterPublicCountersByCurrencies($publicCounters,
            $userCurrencies);

        $activeSellers = Support::find()->active()->anySeller()->select(['id'])->column();

        $counters = $this->service->getPrivateCounters($activeSellers, CustomerQueryService::CONTEXT_NEXT_CARD);

        $privateCountersForUser = CustomerQueryService::filterPrivateCountersByUser($counters, 2);

        $composed = CustomerQueryService::composeCountersForUser($userId, $publicCountersForUser,
            $privateCountersForUser);

        $this->assertTrue(is_array($composed));

        $this->assertArrayHasKey('demo_common', $composed);
        $this->assertArrayHasKey('VND', $composed['demo_common']);
        $this->assertArrayHasKey('public', $composed['demo_common']['VND']);
        $this->assertEquals(1, $composed['demo_common']['VND']['public']);

        $labels = CustomerQueryService::composeLabelsForUser($composed);

        $this->assertTrue(is_array($labels));
        $this->assertArrayHasKey('demo_common', $labels);
        $this->assertEquals('<span class="label label-primary" title="">VND : 1</span>', $labels['demo_common']);
    }

    public function testGetTypeStringById()
    {


        $this->assertEquals('demo_no_trades', CustomerQueryService::getTypeStringById(1));
        $this->assertEquals('demo_active', CustomerQueryService::getTypeStringById(2));
        $this->assertEquals('real_stopped', CustomerQueryService::getTypeStringById(3));
        $this->assertEquals('real_active', CustomerQueryService::getTypeStringById(4));
        $this->assertEquals('real_demo_active', CustomerQueryService::getTypeStringById(5));
        $this->assertEquals('real_inactive', CustomerQueryService::getTypeStringById(6));


    }

    public function testMapTypesById()
    {

        $mapContextModal = CustomerQueryService::mapTypesById(CustomerQueryService::CONTEXT_MODAL);

        $this->assertArrayHasKey('demo_no_trades', $mapContextModal);
        $this->assertArrayHasKey('demo_active', $mapContextModal);
        $this->assertArrayHasKey('real_stopped', $mapContextModal);
        $this->assertArrayHasKey('real_active', $mapContextModal);
        $this->assertArrayHasKey('real_demo_active', $mapContextModal);
        $this->assertArrayHasKey('real_inactive', $mapContextModal);

        $this->assertEquals(1, $mapContextModal['demo_no_trades']);
        $this->assertEquals(2, $mapContextModal['demo_active']);
        $this->assertEquals(3, $mapContextModal['real_stopped']);
        $this->assertEquals(4, $mapContextModal['real_active']);
        $this->assertEquals(5, $mapContextModal['real_demo_active']);
        $this->assertEquals(6, $mapContextModal['real_inactive']);


        $mapContextNextCard = CustomerQueryService::mapTypesById(CustomerQueryService::CONTEXT_NEXT_CARD);

        $this->assertArrayHasKey('demo_common', $mapContextNextCard);
        $this->assertArrayHasKey('demo_ip_app_recall', $mapContextNextCard);
        $this->assertArrayHasKey('demo_ip_app_empty_recall', $mapContextNextCard);
        $this->assertArrayHasKey('real_stopped', $mapContextNextCard);
        $this->assertArrayHasKey('real_inactive', $mapContextNextCard);
        $this->assertArrayHasKey('demo_nop_common', $mapContextNextCard);
        $this->assertArrayHasKey('real_demo_active', $mapContextNextCard);

        $this->assertEquals(1, $mapContextNextCard['demo_common']);
        $this->assertEquals(2, $mapContextNextCard['demo_ip_app_recall']);
        $this->assertEquals(4, $mapContextNextCard['demo_ip_app_empty_recall']);
        $this->assertEquals(5, $mapContextNextCard['real_stopped']);
        $this->assertEquals(6, $mapContextNextCard['real_inactive']);
        $this->assertEquals(8, $mapContextNextCard['demo_nop_common']);
        $this->assertEquals(9, $mapContextNextCard['real_demo_active']);


    }

    public function testGetHybridTypes()
    {

        $types = CustomerQueryService::getHybridTypes();

        $this->assertTrue(is_array($types));

        $this->assertTrue(in_array('demo_ip_app_recall', $types));
        $this->assertTrue(in_array('demo_ip_app_empty_recall', $types));
        $this->assertTrue(in_array('real_stopped', $types));
        $this->assertTrue(in_array('real_inactive', $types));
        $this->assertTrue(in_array('real_demo_active', $types));
        $this->assertFalse(in_array('demo_common', $types));
        $this->assertFalse(in_array('demo_nop_common', $types));
        $this->assertFalse(in_array('demo_active', $types));
        $this->assertFalse(in_array('real_active', $types));
        $this->assertFalse(in_array('demo_no_trades', $types));
    }

    public function testIsHybridType()
    {

        $this->assertTrue(CustomerQueryService::isHybridType('demo_ip_app_recall'));
        $this->assertTrue(CustomerQueryService::isHybridType('demo_ip_app_empty_recall'));
        $this->assertTrue(CustomerQueryService::isHybridType('real_stopped'));
        $this->assertTrue(CustomerQueryService::isHybridType('real_inactive'));
        $this->assertTrue(CustomerQueryService::isHybridType('real_demo_active'));
        $this->assertFalse(CustomerQueryService::isHybridType('demo_common'));
        $this->assertFalse(CustomerQueryService::isHybridType('demo_nop_common'));
        $this->assertFalse(CustomerQueryService::isHybridType('demo_active'));
        $this->assertFalse(CustomerQueryService::isHybridType('real_active'));
        $this->assertFalse(CustomerQueryService::isHybridType('demo_no_trades'));
    }


    public function testIsModeAllowed()
    {


        $this->assertTrue(CustomerQueryService::isModeAllowed('public'));
        $this->assertTrue(CustomerQueryService::isModeAllowed('private'));
        $this->assertFalse(CustomerQueryService::isModeAllowed('hybrid'));
    }

    public function testIsTypeReal()
    {

        $this->assertFalse($this->service->isTypeReal('demo_common'));
        $this->assertTrue($this->service->isTypeReal('real_inactive'));
        $this->assertTrue($this->service->isTypeReal('real_demo_active'));
        $this->assertTrue($this->service->isTypeReal('real_stopped'));
        $this->assertTrue($this->service->isTypeReal('real_active'));

    }

    /**
     * проверка, что запросы в контексте next_card выполняются бех ошибок (без проверки результата)
     *
     * @todo слишком запутано, переделать через dataProvider + switch-case
     */
    public function testGetQueryForContextNextCard()
    {
        $userId = 2;
        $params = [];

        $typesForTestPublic = [

            CustomerQueryService::TYPE_DEMO_COMMON,
            CustomerQueryService::TYPE_DEMO_NOT_ON_PHONE_COMMON
        ];

        $params['currencySet'] = ['VND'];
        $params['currency'] = 'VND';

        foreach ($typesForTestPublic as $type) {
            $query = $this->service->getQueryForContextNextCard($type, 'public', $params);

            $this->assertInstanceOf(ActiveQuery::class, $query);

            $data = $query->all();

            $this->assertTrue(is_array($data));
        }

        $typesForTest = [
            'demo_ip_app_recall',
            'demo_ip_app_empty_recall',
            'real_stopped',
            'real_demo_active',
        ];

        $params['currencySet'] = ['VND'];

        foreach ($typesForTest as $type) {

            $query = $this->service->getQueryForContextNextCard($type, 'public', $params);

            $this->assertInstanceOf(ActiveQuery::class, $query);

            $data = $query->asArray()->all();

            $this->assertTrue(is_array($data));

        }

        // проверка содержимого, возвращаемого запросом
        // задаём только одну валюту, в выборка должна попасть только она
        $params['currencySet'] = ['VND'];
        $query = $this->service->getQueryForContextNextCard('demo_ip_app_recall', 'public', $params);

        $data = $query->asArray()->all();

        $ids = ArrayHelper::getColumn($data, 'id');
        $currencies = ArrayHelper::getColumn($data, 'currency');

//        var_dump($currencies); die();
        // ожидаем, что попадётся клиент c этой влютой
        $this->assertTrue(in_array('VND', $currencies));
        $this->assertTrue(in_array(112, $ids));
        // и что не попадётся клиент c другой
        $this->assertFalse(in_array(113, $ids));
        $this->assertFalse(in_array('INR', $currencies));

        $params['currencySet'] = ['VND', 'INR'];

        $query = $this->service->getQueryForContextNextCard('demo_ip_app_recall', 'public', $params);

        $data = $query->asArray()->all();

        $ids = ArrayHelper::getColumn($data, 'id');
        $currencies = ArrayHelper::getColumn($data, 'currency');

        // ожидаем, что попадётся клиент c этой влютой
        $this->assertTrue(in_array('VND', $currencies));
        $this->assertTrue(in_array(112, $ids));
        $this->assertTrue(in_array(113, $ids));
        $this->assertTrue(in_array('INR', $currencies));
        // добавляем параметр, чтобы проверить частные очереди
        $params['user_id'] = $userId;

        foreach ($typesForTest as $type) {

            $query = $this->service->getQueryForContextNextCard($type, 'private', $params);

            $this->assertInstanceOf(ActiveQuery::class, $query);

            $data = $query->all();

            $this->assertTrue(is_array($data));
        }


    }

    public function testGetQueryForContextNextCardExceptionNotAllowedForContext()
    {

        $this->expectException(CustomerQueryServiceException::class);
        $this->expectExceptionMessage('type demo_active is not allowed for context next_card');

        $this->service->getQueryForContextNextCard('demo_active', 'public');

    }

    public function testGetQueryForContextNextCardExceptionMissedUserId()
    {

        $this->expectException(CustomerQueryServiceException::class);
        $this->expectExceptionMessage('missed required param user_id; type demo_ip_app_recall');

        $this->service->getQueryForContextNextCard(CustomerQueryService::TYPE_DEMO_IN_PROGRESS_RECALL, 'private');
    }

    public function testGetQueryForContextNextCardExceptionMissedCurrencySet()
    {

        $this->expectException(CustomerQueryServiceException::class);
        $this->expectExceptionMessage('currencySet param required for public mode of type demo_ip_app_recall');

        $this->service->getQueryForContextNextCard(CustomerQueryService::TYPE_DEMO_IN_PROGRESS_RECALL, 'public',
            ['user_id' => 2]);
    }

    public function testGetQueryForContextNextCardExceptionMissedCurrency()
    {

        $this->expectException(CustomerQueryServiceException::class);
        $this->expectExceptionMessage('missed required param currencySet; type demo_common');

        $this->service->getQueryForContextNextCard('demo_common', 'public');
    }

    /**
     *
     */
    public function testGetDemoRecentPaymentFailedQuery()
    {

        $query = $this->service->getDemoRecentPaymentFailedQuery();

        $this->assertInstanceOf(ActiveQuery::class, $query);

        $data = $query->all();

        $this->assertTrue(is_array($data));
    }

    public function testApplyPublicCardsCondition()
    {

        $query = $this->service->getDemoNoActivityOneDay();

        $query = $this->service->applyPublicCardsCondition($query, 'VND');

        $this->assertInstanceOf(ActiveQuery::class, $query);

        $data = $query->all();

        $this->assertTrue(is_array($data));
    }

    public function testApplyPersonalCardsCondition()
    {

        $query = $this->service->getDemoNoActivityOneDay();

        $query = $this->service->applyPersonalCardsCondition($query, 2);

        $this->assertInstanceOf(ActiveQuery::class, $query);

        $data = $query->all();

        $this->assertTrue(is_array($data));
    }

    public function testAllowedCardsForUsersQuery()
    {

        $query = $this->service->getDemoNoActivityOneDay();

        $users = Support::find()->active()->select('id')->column();

        $cardsQuery = $this->service->allowedCardsForUsersQuery($users);

        $this->assertInstanceOf(Query::class, $cardsQuery);

        $query->andWhere([
            'or',
            new Expression('NOT EXISTS (select 1 from cards c WHERE c.customer_id = u.id)'),
            new Expression('EXISTS (' . $cardsQuery->createCommand()->rawSql) . ')'
        ]);

        $data = $query->all();

        $this->assertTrue(is_array($data));
    }

    public function testIsCounterAllowedForType()
    {

        $this->assertTrue($this->service->isCounterAllowedForType('demo_common'));
        $this->assertTrue($this->service->isCounterAllowedForType('demo_nop_common'));
        $this->assertTrue($this->service->isCounterAllowedForType('demo_ip_app_recall'));
        $this->assertTrue($this->service->isCounterAllowedForType('demo_ip_app_empty_recall'));
        $this->assertTrue($this->service->isCounterAllowedForType('real_stopped'));
        $this->assertTrue($this->service->isCounterAllowedForType('real_inactive'));
        $this->assertTrue($this->service->isCounterAllowedForType('real_demo_active'));
        $this->assertTrue($this->service->isCounterAllowedForType('demo_no_activity_1day'));
        $this->assertTrue($this->service->isCounterAllowedForType('demo_no_activity_3hours'));

        $this->assertFalse($this->service->isCounterAllowedForType('demo_no_refill'));
    }

    public function testGetSmartTypes()
    {

        $types = $this->service->getSmartTypes();

        $this->assertTrue(in_array(CustomerQueryService::TYPE_DEMO_NO_ACTIVITY_3_HOURS, $types));
        $this->assertTrue(in_array(CustomerQueryService::TYPE_DEMO_NO_ACTIVITY_1_DAY, $types));
        $this->assertTrue(in_array(CustomerQueryService::TYPE_DEMO_NO_FILLUP, $types));
        $this->assertTrue(in_array(CustomerQueryService::TYPE_DEMO_NO_REFILL, $types));
        $this->assertTrue(in_array(CustomerQueryService::TYPE_DEMO_NO_TOURNAMENTS, $types));
        $this->assertTrue(in_array(CustomerQueryService::TYPE_ALL_NO_PAY_TOURNAMENTS, $types));
        $this->assertTrue(in_array(CustomerQueryService::TYPE_DEMO_RECENT_PAYMENT_FAILED, $types));
    }

}
