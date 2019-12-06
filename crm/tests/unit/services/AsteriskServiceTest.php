<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 20.03.19
 * Time: 12:29
 */

namespace app\tests\unit\services;


use app\exceptions\AsteriskServiceException;
use tests\fixtures\CountryFixture;
use tests\fixtures\SupportsFixture;
use tests\unit\BaseUnit;
use yii\helpers\ArrayHelper;

/**
 * Class AsteriskServiceTest
 * @package app\tests\unit\services
 */
class AsteriskServiceTest extends BaseUnit
{
    /**
     * @var \app\tests\mock\asterisk\AsteriskService
     */
    protected $service;

    /* @inheritdoc */
    protected function setUp()
    {
        parent::setUp();
        $this->service = new \app\tests\mock\asterisk\AsteriskService();
    }

    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'country'              => CountryFixture::class,
            'users'              => SupportsFixture::class
        ];
    }

    /**
     *
     */
    public function testValidateIncomingTasks()
    {

        $data = $this->service->getIncomingTasks();

        $wrongData = $this->getWrongIncomingTasks();

        $this->service->validateIncomingTasks($data);

        $this->expectException(AsteriskServiceException::class);

        $this->service->validateIncomingTasks($wrongData);

        $this->expectException(AsteriskServiceException::class);

        $wrongTasks = 'wrong';

        $this->service->validateIncomingTasks($wrongTasks);
    }

    /**
     *
     */
    public function testComposingDataForConfig(){

        $data = $this->getTasksFromManagerPost();

        //@todo использовать метод сервиса, добавить данные в фиксутры при необходимости, чтобы было по крайней мере 2 юзера
        // чтобы можно было также протестировать удаление юзера из конфига
        $users= $this->service->getUsersForConfig();

        $config = $this->service->composeDataForSetConfig($data, $users);

        $this->assertTrue(is_array($config));

        $this->assertTrue(count($config) > 0);

        $this->assertArrayHasKey(0, $config);

        $configItem = $config[0];

        $this->assertArrayHasKey('user_id', $configItem);
        $this->assertArrayHasKey('phone', $configItem);
        $this->assertArrayHasKey('country', $configItem);
        $this->assertArrayHasKey('queues', $configItem);

        $this->assertEquals(13, $configItem['user_id']);
        $this->assertEquals('35777788218', $configItem['phone']);
        $this->assertEquals('IND', $configItem['country']);

        $this->assertTrue(count($configItem['queues']) === 3);

        $queue = $configItem['queues'][0];

        $this->assertArrayHasKey('name', $queue);
        $this->assertArrayHasKey('type', $queue);

        $this->assertEquals('demo_common', $queue['name']);
        $this->assertEquals('public',  $queue['type']);

        $queue = $configItem['queues'][1];

        $this->assertArrayHasKey('name', $queue);
        $this->assertArrayHasKey('type', $queue);

        $this->assertEquals('demo_nop_common',  $queue['name']);
        $this->assertEquals('public',  $queue['type']);

        $queue = $configItem['queues'][2];

        $this->assertArrayHasKey('name', $queue);
        $this->assertArrayHasKey('type', $queue);

        $this->assertEquals('demo_ip_app_recall',  $queue['name']);
        $this->assertEquals('private',  $queue['type']);

    }

    public function testGetUsersForConfig(){

        $users = $this->service->getUsersForConfig();
        $this->assertTrue(is_array($users));

    }


    /**
     * проверка на то, что юзер был в конфиге и затем исключён из конфига
     */
    public function testRemoveUserFromConfig(){

        $userIdForRemove = 15;
        $userIdExisted = 13;

        $tasks = $this->service->getIncomingTasks();

//        var_dump($tasks); die();

        // адаптируем структуру массива, чтобы далее получились правильные название очредей
        $adapt = $this->service->adaptDataForComposeConfig($tasks);

        $users = $this->service->getUsersForConfig();
        // собираем полный конфиг для отправки
        $config  = $this->service->composeDataForSetConfig($adapt, $users);
        // юсключаем текущего юзера из собранного конфига

        $usersFromConfig = ArrayHelper::getColumn($config,'user_id');

        $this->assertTrue(in_array($userIdExisted, $usersFromConfig));
        $this->assertTrue(in_array($userIdForRemove, $usersFromConfig));

        $updatedConfig = $this->service->removeUserFromConfig($config, $userIdForRemove);

        $usersFromUpdatedConfig = ArrayHelper::getColumn($updatedConfig,'user_id');

        $this->assertTrue(in_array($userIdExisted, $usersFromUpdatedConfig));
        $this->assertFalse(in_array($userIdForRemove, $usersFromUpdatedConfig));

    }

    /**
     *
     */
    public function testProcessRemoveFromConfig(){

        $this->assertTrue($this->service->processRemoveFromConfig(15));

    }

    public function testAdaptDataForComposeConfig(){

        $incoming = $this->service->getIncomingTasks();

        $adapt = $this->service->adaptDataForComposeConfig($incoming);

        $this->assertArrayHasKey('common', $adapt);
        $this->assertArrayHasKey('private', $adapt);

        $common = $adapt['common'];

        $private = $adapt['private'];


        $this->assertArrayHasKey('IND', $common);
        $this->assertArrayHasKey('THA', $common);
        $this->assertArrayHasKey('VNM', $common);

        $IND = $common['IND'];

        $this->assertArrayHasKey('demo_common', $IND);
        $this->assertArrayHasKey('demo_nop_common', $IND);

        $this->assertEquals(1, $IND['demo_common']);
        $this->assertEquals(1, $IND['demo_nop_common']);


        $this->assertArrayHasKey(13, $private);
        $this->assertArrayHasKey(15, $private);
        $this->assertArrayHasKey(42, $private);

        $this->assertArrayHasKey('demo_ip_app_recall', $private[13]);
        $this->assertArrayHasKey('demo_in_progress_approve', $private[13]);

        $this->assertEquals(1, $private[13]['demo_ip_app_recall']);
        $this->assertEquals(1, $private[13]['demo_in_progress_approve']);
    }


    /**
     * эмуляция массива Post из asterisk-queue/manager, прилтающег в asterisk-queue/manager-set-config
     *
     * @return array
     */
    public function getTasksFromManagerPost(){

        return [
            "private" => [
                13 => [
                    "demo_ip_app_recall" => 1
                ],
                15 => [
                    "real_stopped" => 1
                ],
                42 => [
                    "real_inactive" => 1
                ],
            ],
            "common" => [
                "IND" => [
                    "demo_common" =>1,
                    "demo_nop_common" => 1
                ],
                "THA" => [
                    "demo_common" => 1,
                    "demo_nop_common" => 1
                ],
                "VNM" => [
                    "demo_common" => 1,
                    "demo_nop_common" => 1
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function getWrongIncomingTasks(){

         return  [
            "privates" => [
                13 => [
                    "demo_in_progress_approve"
                ],
            ],
            "common" => [
                "IND" => [
                    "demo_common",
                    "demo_not_on_phone_common"
                ],
            ]
        ];
    }

}
