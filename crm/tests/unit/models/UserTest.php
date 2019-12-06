<?php
namespace tests\models;
use app\helpers\RbacHelper;
use app\models\Support;
use app\tests\fixtures\AdminAttachedUsersFixture;
use tests\unit\BaseUnit;
use tests\fixtures\SupportsFixture;

class UserTest extends BaseUnit
{
    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'supports' => SupportsFixture::class,
            'attached_users' => AdminAttachedUsersFixture::class,
        ];
    }

    public function testFindUserById()
    {
        $this->assertNotNull($user = Support::findIdentity(1));
        $this->assertEquals($user->getLogin(), 'admin');

        $this->assertNotNull($user = Support::findIdentity(2));
        $this->assertEquals($user->getLogin(), 'seller_1');

        $this->assertNull(Support::findIdentity(999));
    }


    /**
     * тест на сохранение юзера с изменённым набором валют
     */
    public function testSaveExistedUpdateCurrencies(){

       $user = Support::findIdentity(5);

       $user->currencies = ['VND', 'USD'];

       $this->assertTrue($user->save());
    }

    public function testGetAdminAttachedUsersIds(){

        $user = Support::findIdentity(1);

        $this->assertTrue(RbacHelper::isUserHasRole(1, 'admin'));

        $users = $user->getAdminAttachedUsersIds();

        $this->assertTrue(in_array(2, $users));
        $this->assertTrue(in_array(3, $users));
        $this->assertFalse(in_array(4, $users));
    }
}
