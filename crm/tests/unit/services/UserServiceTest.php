<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 01/07/2019, 16:54
 */

namespace tests\services;

use app\exceptions\UserServiceException;
use app\helpers\param\ExternalAppParam;
use app\models\Customers;
use app\services\UserService;
use tests\fixtures\CustomersFixture;
use tests\mock\sdk\UserResource;
use tests\unit\BaseUnit;

/**
 * Class UserServiceTest
 * @package tests\services
 */
class UserServiceTest extends BaseUnit
{
    /**
     * @var UserService
     */
    protected $service;

    /* @inheritdoc */
    protected function setUp()
    {
        parent::setUp();
        $this->service = \Yii::$container->get(UserService::class);
    }

    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'customers_synced' => CustomersFixture::class,
        ];
    }

    /** @see UserResource::getAuthToken() */
    public function testGetAuthToken()
    {
        $userKey = '12345abcde';
        $token = $this->service->getAuthToken($userKey);

        $this->assertEquals($token, $userKey. '-token');
    }

    public function testGetUpdatePasswordLink()
    {
        $token = '12345abcde-token';
        $resetPasswordLink = $this->service->getUpdatePasswordLink($token);

        $this->assertEquals(ExternalAppParam::getUrl() . '/password-update?token=' . $token, $resetPasswordLink);
    }

    /**
     * @return array
     */
    public function getPromoCodeDataProvider(): array
    {
        return [
            [1],
            [2],
            [3],
            [4]
        ];
    }

    /**
     * @dataProvider getPromoCodeDataProvider
     *
     * @see UserResource::getPromoCodes()
     * @param $i
     */
    public function testGetPromoCode($i)
    {
        switch ($i) {
            case 1:

                $this->expectException(UserServiceException::class);
                $this->expectExceptionMessage('Error get promo code: Test case return error');
                $this->service->getPromoCode($i);
                break;

            case 2:
                $this->expectException(UserServiceException::class);
                $this->expectExceptionMessage('Error API response format');
                $this->service->getPromoCode($i);
                break;

            case 3:
                $this->expectException(UserServiceException::class);
                $this->expectExceptionMessage('Code has expired');
                $this->service->getPromoCode($i);
                break;

            case 4:
                $promoCode = $this->service->getPromoCode($i);
                $this->assertCount(2, $promoCode);
                $this->assertArrayHasKey('code', $promoCode);
                $this->assertArrayHasKey('percent', $promoCode);
                $this->assertEquals('ABCDE', $promoCode['code']);
                $this->assertEquals(50, $promoCode['percent']);
                break;
        }
    }

    public function testResetPassword()
    {
        $this->assertTrue($this->service->resetPassword(12345));
    }

    public function testActive()
    {
        $customerId = 130;
        $this->assertNotNull($customer = Customers::findOne($customerId));

        $this->assertTrue($this->service->activate($customerId, false));
        $this->assertTrue($customer->refresh());
        $this->assertEquals(0, $customer->active);

        $this->assertTrue($this->service->activate($customerId, true));
        $this->assertTrue($customer->refresh());
        $this->assertEquals(1, $customer->active);
    }
}
