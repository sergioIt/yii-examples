<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 03.09.2018, 15:44
 */

namespace app\components\api;

/**
 * Class ITexMoSmsApi
 * @package app\components\api
 */
class ITexMoSmsApi extends SmsApi
{
    /**
     * @var string
     */
    public $apiCode;

    /**
     * @var string
     */
    public $fromName = 'IronTrade';

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'itexmo';
    }

    /**
     * Send sms-message
     *
     * @param string $number
     * @param string $message
     *
     * @return bool
     * @throws \yii\base\Exception
     */
    public function send(string $number, $message): bool
    {
        $number  = trim($number);
        $message = trim($message);

        if ( ! $number || ! $message) {
            return false;
        }

        $data = [
            '1' => $number,
            '2' => $message,
            '3' => $this->apiCode,
            '6' => $this->fromName,
        ];

        $param = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ]
        ];

        $context = stream_context_create($param);
        $response = file_get_contents($this->getBaseUrl(), false, $context);

        if ($response == '') {
            $this->error('No response from server');
        } elseif ($response == 0) {
            $this->infoLog('SMS message to ' . $number . ' was successfully sent. Text: ' . $message . '.');
            return true;
        } else {
            $this->error("Phone number: $number\n iTexMo error num: $response");
        }

        return false;
    }
}
