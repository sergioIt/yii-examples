<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 07.07.17
 * Time: 16:48
 */

namespace app\components;

use app\helpers\param\TelegramParam;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use yii\base\Exception;
use yii\helpers\Json;

/**
 * Class Telegram
 * @package app\components
 *
 * Wrapper for Telegram API
 */
class Telegram
{

    const API_BASE_URL = 'https://api.telegram.org/';

    const SEND_MESSAGE_COMMAND = 'sendMessage';

    const GET_BOT_UPDATES_COMMAND = 'getUpdates';

    /**
     *
     * Send text message from bot to specified chat
     *
     * @param $chatId
     * @param $text
     * @return  bool if message sent ot not
     * @throws Exception
     */
    public static function sendMessageFromBot(string $chatId, $text){

        $botKey = TelegramParam::botKey();

        if($botKey === null) {

            throw  new Exception('missed telegram bot key is not specified at app params');
        }

        try{

            $client = new Client();

            $resp = $client->request('POST', self::API_BASE_URL.'bot'.$botKey.'/'.self::SEND_MESSAGE_COMMAND,
                [
                    'form_params' => ['chat_id' => $chatId,
                        'text' => $text],
                ]

            );

            // если код ответа 200, то считаем, что сообщение отправлено
            return ($resp->getStatusCode() === 200) ? true : false;

        }
        catch (ClientException $e){

            \Yii::error($e->getMessage());
        }
        catch (GuzzleException $e) {
            \Yii::error($e->getMessage());
        }


        return  false;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function getBotUpdates(){

        $botKey = TelegramParam::botKey();

        if($botKey === null) {

            throw  new Exception('missed telegram bot key is not specified at app params');
        }

        $url = self::API_BASE_URL.'bot'.$botKey.'/'.self::GET_BOT_UPDATES_COMMAND;

        try{

            $client = new Client();

           $resp = $client->request('GET', $url);

            return Json::decode($resp->getBody());
        }
        catch (ClientException $e){

            \Yii::error($e->getMessage());
        }
        catch (GuzzleException $e) {
            \Yii::error($e->getMessage());
        }


        return [];
    }

    /**
     *
     * Parse response of "getUpdates" command
     * to get compact array of data: user - chat_id
     *
     * @param array $response
     *
     * @return array
     */
    public static function parseUpdatesResponse(array $response){

        $result = [];

        if(! array_key_exists('result', $response)){

            return null;
        }

       $responseResult = $response['result'];

        if(count($responseResult) ===0){

            return [];
        }

        $chatIds = [];

        foreach ($responseResult as $data){

            $messageFrom = $data['message']['from'];

            $messageChat = $data['message']['chat'];

            $from = '';

            if(array_key_exists('first_name',$messageFrom)){

                $from .= $messageFrom['first_name'];
            }

            if(array_key_exists('last_name', $messageFrom)){

                $from .= ' '. $messageFrom['last_name'];
            }

            // check array of chat id to prevent duplicates updates
            if(! in_array($messageChat['id'], $chatIds)){

                $chatIds[] = $messageChat['id'];
                $result[$data['update_id']]['from'] = $from;
                $result[$data['update_id']]['chat_id'] = $messageChat['id'];
            }

        }

        return $result;
    }
}
