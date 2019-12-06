<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 23.08.19
 * Time: 16:18
 */

namespace app\tests\unit\services;


use app\helpers\RbacHelper;
use app\services\CustomerModalService;
use app\tests\fixtures\TournamentOperationFixture;
use tests\fixtures\AuthAssignmentFixture;
use tests\fixtures\AuthItemFixture;
use tests\fixtures\CustomersFixture;
use tests\fixtures\OperationArchiveFixture;
use tests\fixtures\OperationFixture;
use tests\fixtures\SupportsFixture;
use tests\unit\BaseUnit;
use yii\base\InvalidConfigException;
use yii\db\Exception;

/**
 * Class CustomerModalServiceTest
 * @package app\tests\unit\services
 */
class CustomerModalServiceTest extends BaseUnit
{
    /** @inheritdoc */
    public function fixtures(): array
    {
        return [

            'support' => SupportsFixture::class,
            'customers' => CustomersFixture::class,
            'operations' => OperationFixture::class,
            'operations_archive' => OperationArchiveFixture::class,
            'auth_items' => AuthItemFixture::class,
            'auth_assignment' => AuthAssignmentFixture::class,
            'operations_t' => TournamentOperationFixture::class,
        ];
    }

    /**
     * тестируется данные, которые запрашивается на строне сrm (запросы к базе данных)
     *  запроы к private-api (referrals) не тестируются,
     *  хотя попытка запросы просиходит
     */
    public function testGetCheckModalButtons(){

        try {

            $userId = 766553;
            $service = new CustomerModalService($userId);

            $data = $service->getCheckModalButtons($userId);
            $this->assertArrayHasKey('operations_demo', $data);
            $this->assertArrayHasKey('operations_real', $data);
            $this->assertArrayHasKey('operations_archive', $data);
            $this->assertArrayHasKey('operations_tournament', $data);
            $this->assertArrayHasKey('payments', $data);
            $this->assertArrayHasKey('withdraw', $data);
            $this->assertArrayHasKey('referrals', $data);
            $this->assertArrayHasKey('balance_history', $data);
            $this->assertArrayHasKey('call_history', $data);
            $this->assertArrayHasKey('send_mail', $data);


            // проеряем, что к текущему юзеру применилась роль 'seller'
            // потому что от роли зависит результат проверок
            $this->assertTrue(RbacHelper::can('seller')) ;

            $this->assertEquals(1, $data['operations_demo']);
            $this->assertEquals(1, $data['operations_real']);
            $this->assertEquals(1, $data['operations_archive']);
            $this->assertEquals(1, $data['operations_tournament']);
            $this->assertEquals(0, $data['payments']);
            $this->assertEquals(0, $data['withdraw']);
            $this->assertEquals(0, $data['balance_history']);
            $this->assertEquals(0, $data['call_history']);
            $this->assertEquals(1, $data['send_mail']);


        } catch (Exception $e) {
        } catch (InvalidConfigException $e) {
        }


    }
}
