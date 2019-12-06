<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 23.07.19
 * Time: 12:27
 */

namespace app\tests\mock\asterisk;

/**
 * Переопределение методов AsteriskService, которые обращаются ко внешнему урлу
 *
 * Class AsteriskService
 * @package app\tests\mock\asterisk
 */
class AsteriskService extends \app\services\AsteriskService
{

    /**
     * @return array
     */
    public function getAllPhones(){

        return [
            array (
                0 =>
                    array (
                        'id' => '89',
                        'country' => 'CYP',
                        'phone' => '+35722000443',
                    ),
                1 =>
                    array (
                        'id' => '97',
                        'country' => 'IDN',
                        'phone' => '+622150851234',
                    ),
                2 =>
                    array (
                        'id' => '81',
                        'country' => 'THA',
                        'phone' => '+6625088663',
                    ),
                3 =>
                    array (
                        'id' => '73',
                        'country' => 'VNM',
                        'phone' => '+842444582123',
                    ),
                4 =>
                    array (
                        'id' => '61',
                        'country' => 'IND',
                        'phone' => '+918000401806',
                    ),
                5 =>
                    array (
                        'id' => '106',
                        'country' => 'NGA',
                        'phone' => '23412279019',
                    ),
            )
        ];

    }

    /**
     * @return array
     */
    public function getIncomingTasks(){

        return [
            "private" => [
                13 => [
                    "demo_ip_app_recall",
                    "demo_in_progress_approve"
                ],
                15 => [
                    "real_stopped"
                ],
                42 => [
                    "real_inactive"
                ],
            ],
            "common" => [
                "IND" => [
                    "demo_common",
                    "demo_nop_common"
                ],
                "THA" => [
                    "demo_common",
                    "demo_nop_common"
                ],
                "VNM" => [
                    "demo_common",
                    "demo_nop_common"
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function getUsersForConfig(){

        return  [
            0 =>
                [
                    'id' => 13,
                    'login' => 'test_seller2',
                    'code' => 'IND',
                    'phone' => '35777788218',
                ],

            1 =>
                [
                    'id' => 15,
                    'login' => 'user_15',
                    'code' => 'IND',
                    'phone' => '35777788218',
                ],
        ];

    }

    public function getActualTasks(){

        return [
            "private" => [
                13 => [
                    "demo_ip_app_recall",
                    "demo_in_progress_approve"
                ],
                15 => [
                    "real_stopped"
                ],
                42 => [
                    "real_inactive"
                ],
            ],
            "common" => [
                "IND" => [
                    "demo_common",
                    "demo_nop_common"
                ],
                "THA" => [
                    "demo_common",
                    "demo_nop_common"
                ],
                "VNM" => [
                    "demo_common",
                    "demo_nop_common"
                ]
            ]
        ];
    }

    public function processRemoveFromConfig(int $userId)
    {
        // получаем список тасков с api
        $tasks = $this->getActualTasks();

        if ($tasks === []) {

            return true;
        }

        // адаптируем структуру массива, чтобы далее получились правильные название очредей
        $adapt = $this->adaptDataForComposeConfig($tasks);

        $users = $this->getUsersForConfig();

        if ($users === []) {

            return true;
        }

        // собираем полный конфиг для отправки
        $config = $this->composeDataForSetConfig($adapt, $users);
        // юсключаем текущего юзера из собранного конфига
        $updatedConfig = $this->removeUserFromConfig($config, $userId);
        // если после исключения конфиг не изменился, нет смысла отправлять
        if ($config === $updatedConfig) {

            return true;
        }

        $this->sendConfig($updatedConfig);

        $log['message'] = 'remove user id from config';
        $log['reason'] = 'user was fired';
        $log['user_id'] = $userId;
        $log['web_user_id'] = \Yii::$app->user->getId();
        $this->log($log);

        return true;
    }

    /**
     * @param array $config
     * @return bool
     */
    public function sendConfig(array $config){

        return true;
    }

}
