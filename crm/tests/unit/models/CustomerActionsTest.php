<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 10.07.19
 * Time: 18:24
 */

namespace app\tests\unit\models;


use app\helpers\SendPulseMail;
use app\models\CustomerActions;
use app\models\Customers;
use app\models\Support;
use tests\fixtures\CustomerActionsFixture;
use tests\fixtures\CustomersFixture;
use tests\fixtures\SupportsFixture;
use tests\unit\BaseUnit;
use yii\base\Exception;

/**
 * Class CustomerActionsTest
 * @package app\tests\unit\models
 */
class CustomerActionsTest extends BaseUnit
{

    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'actions' => CustomerActionsFixture::class,
            'customers' => CustomersFixture::class,
            'users' => SupportsFixture::class,
        ];
    }

    public function testSuccessWrite(){

        $userId = 2;

        $this->assertNotNull(Support::findOne($userId)) ;

        $ca = CustomerActions::write(CustomerActions::TYPE_VERIFY_EMAIL, 766553, $userId);

        $this->assertInstanceOf(CustomerActions::class, $ca);
    }

    public function testExceptionNotExistedUser(){

        $userId = 12;
        $customerId = 766553;

        $this->assertNull(Support::findOne($userId)) ;
        $this->assertNotNull(Customers::findOne($customerId)) ;

        $this->expectException(Exception::class);

        CustomerActions::write(CustomerActions::TYPE_VERIFY_EMAIL, $customerId, $userId);
    }

    public function testExceptionNotExistedCustomer(){

        $userId = 2;
        $customerId = 76;

        $this->assertNotNull(Support::findOne($userId)) ;
        $this->assertNull(Customers::findOne($customerId)) ;

        $this->expectException(Exception::class);

        CustomerActions::write(CustomerActions::TYPE_VERIFY_EMAIL, $customerId, $userId);
    }

    public function testExceptionNotAllowedType(){

        $userId = 2;
        $customerId = 76653;

        $type = 'fake';

        $this->assertNotNull(Support::findOne($userId)) ;
        $this->assertNotNull(Customers::findOne($customerId)) ;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Not allowed type: '.$type);

        CustomerActions::write($type, $customerId, $userId);
    }

    public function testGetTypeForSendPulse(){

        $this->assertNull(CustomerActions::getTypeForSendPulse('not_existed'));

        $this->assertEquals(CustomerActions::TYPE_EMAIL_RESET_PASSWORD, CustomerActions::getTypeForSendPulse(SendPulseMail::TYPE_RESTORE_PASSWORD));
        $this->assertEquals(CustomerActions::TYPE_EMAIL_APP_INVITE, CustomerActions::getTypeForSendPulse(SendPulseMail::TYPE_MOBILE_APP_INVITATION));

    }
}
