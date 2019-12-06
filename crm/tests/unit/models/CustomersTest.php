<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 05.06.19
 * Time: 15:52
 */

namespace app\tests\unit\models;


use app\models\Customers;
use app\services\CustomersService;
use app\tests\fixtures\WithdrawFixture;
use tests\fixtures\CustomersFixture;
use tests\fixtures\PaymentsFixture;
use tests\unit\BaseUnit;

/**
 * Class CustomersTest
 * @package app\tests\unit\models
 */
class CustomersTest extends BaseUnit
{
    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'customers' => CustomersFixture::class,
            'p'         => PaymentsFixture::class,
            'w'         => WithdrawFixture::class
        ];
    }


    /**
     * тест переопредлнного метода updateAttributes
     */
    public function testUpdateAttributes()
    {

        /**
         *  тест смены телефона с пустого на правильный
         */
        $this->assertNotNull($customer = Customers::findOne(766553));

        $this->assertNull($customer->phone);
        $this->assertNull($customer->phone_valid);

        // задаём правильный номер, и убеждаемся, что после updateAttributes() поле phone_valid становится true
        $attributes['phone'] = '+84642456214';

        $customer->updateAttributes($attributes);

        $this->assertTrue($customer->phone_valid);

        /**
         * тест смены телефона с правильного на неправильный
         */

        $this->assertNotNull($customer = Customers::findOne(121));
        $this->assertNull($customer->phone_valid);

        $this->assertTrue((new CustomersService())->phoneIsValid($customer->phone, $customer->currency));

        // задаём  неправильный номер, и убеждаемся, что после updateAttributes() поле phone_valid становится false
        $attributes['phone'] = '+85642456214';

        $customer->updateAttributes($attributes);

        $this->assertFalse($customer->phone_valid);


    }

    public function testNewCustomerPhoneValid(){

        /**
         *  тест создания новой записи с правильным телефоном и заданной валютой
         */
        $customer = new Customers();
        $customer->id = 99;
        $data['reg_date'] = 1520343037;
        $data['phone'] = '+84642456214';
        $data['currency'] = 'VND';

        $customer->setAttributes($data);

        $this->assertTrue($customer->save());
        $this->assertTrue($customer->phone_valid);

    }

    /**
     *  тест создания новой записи с правильным телефоном и не заданной валютой - клиент должен сохраниться,
     * валидность телефона определить невозможно
     */
    public function testNewCustomerEmptyCurrency(){

        $customer = new Customers();
        $customer->id = 100;
        $data['reg_date'] = 1520343037;
        $data['phone'] = '+84642456214';

        $customer->setAttributes($data);
        $this->assertTrue($customer->save());

        $this->assertFalse($customer->phone_valid);
    }

    /**
     * тест на то, что баланс отформатирован корректно
     */
    public function testBalanceLabel(){

        // проверка форматирования демо баланса для значения менее 1000
        $this->assertNotNull($customer = Customers::findOne(121));
        $this->assertEquals('390.00', $customer->balance_demo);
        $this->assertFalse($customer->isReal());
        $label = $customer->balanceLabel();

        $this->assertEquals('<span>390,00</span>', $label);

        // проверка форматирования реального баланса для значения менее 1000
        $this->assertNotNull($customer = Customers::findOne(128));
        $this->assertEquals('550', $customer->balance_real);
        $this->assertTrue($customer->isReal());
        $this->assertEquals('<span title="Customer.Balance.Title">550,00 / 0</span>', $customer->balanceLabel());

        // проверка форматирования для значений баланса больше тысячи
        $this->assertNotNull($customer = Customers::findOne(127));
        $this->assertEquals('128000.00', $customer->balance_demo);
        $this->assertFalse($customer->isReal());
        $this->assertEquals('<span>128 000,00</span>', $customer->balanceLabel());

        // проверка форматирования для значений баланса null
        $this->assertNotNull($customer = Customers::findOne(126));
        $this->assertNull($customer->balance_real);
        $this->assertFalse($customer->isReal());

        $this->assertEquals('<span>0</span>',  $customer->balanceLabel());
    }

    /**
     * SOFT / FORCE DELETE
     */
    public function testDelete()
    {
        $userId = 76653;
        $this->assertNotNull($customer = Customers::findOne($userId));
        $this->assertFalse($customer->isDeleted());
        $this->assertTrue($customer->delete());

        # просто так теперь не найден
        $this->assertNull(Customers::findOne($userId));
        $this->assertNull(Customers::find()->where(['id' => $userId])->one());
        $this->assertNotNull($customer = Customers::find(true)->where(['id' => $userId])->one());
        $this->assertTrue($customer->isDeleted());

        $this->assertTrue($customer->delete(true)); # force delete
        $this->assertNull(Customers::find()->where(['id' => $userId])->one());
        $this->assertNull(Customers::find(true)->where(['id' => $userId])->one());
    }

    public function testGetTotalDeposit(){

        $this->assertNotNull($customer = Customers::findOne(111));

        $this->assertEquals('270', $customer->getTotalDeposit());
    }

    public function testGetTotalWithdraw(){

        $this->assertNotNull($customer = Customers::findOne(111));

        $this->assertEquals('100.5', $customer->getTotalWithdraw());
    }
}
