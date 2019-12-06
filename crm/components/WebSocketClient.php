<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 23/11/2018, 18:08
 */

namespace app\components;

use app\daemons\WebSocketServer;
use app\helpers\param\AppParam;
use app\helpers\param\EnvParam;
use vakata\websocket\Client;
use yii\helpers\Json;

/**
 * Class WebSocketClient
 * @package app\components
 */
class WebSocketClient extends Client
{
    const LOG_CATEGORY = 'web-socket-client';

    /**
     * @param array $data
     *
     * @return bool
     * @throws \yii\base\InvalidArgumentException
     */
    public static function sendMessage(array $data)
    {
        $message = Json::encode($data);
        try {
            $client = new self(EnvParam::webSocketUrl());
            return $client->send($message);
        } catch (\Throwable $e) {
            \Yii::error('Error to connect web socket. Message: ' . $e->getMessage(), self::LOG_CATEGORY);
        }
    }
}
