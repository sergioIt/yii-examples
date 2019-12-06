<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 27.08.2018, 15:38
 */

namespace app\components\api;

/**
 * Class VhtSmsApi
 * @package app\components\api
 *
 * @deprecated - он просто не используется, если вдруг будет, нужно обязательно учесть специфику:
 *  сообщения тут задаются не текстом, метод @see SmsApi::sendToCustomer() этого не предусматривает
 */
class VhtSmsApi extends SmsApi
{
    /**
     * Sms template code
     */
    const TEMPLATE_RESET_PASSWORD_EN = 26914;
    const TEMPLATE_RESET_PASSWORD_VN = 26913;

    /**
     * @var string
     */
    public $apiKey;

    /**
     * @var string
     */
    public $apiSecret;

    /**
     * @var string
     */
    public $brandName = '0901800086';

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'vhtsms';
    }

    /**
     * @param string $number
     * @param array $message ['sms_template_code' => int, 'param1' => 'val']
     *
     * @return bool
     */
    public function send(string $number, $message): bool
    {
        try {
            $number = $this->normalizeNumber($number);

            $params = [
                'submission' => [
                    'api_key'    => $this->apiKey,
                    'api_secret' => $this->apiSecret,
                    'sms'        => [
                        [
                            'brandname' => $this->brandName,
                            'text'      => $message,
                            'to'        => $number,
                        ],
                    ],
                ],
            ];

            $json = json_encode($params);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->getBaseUrl());
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($json)
            ]);

            $response = curl_exec($ch);
            $error = curl_error($ch);
            $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($error) {
                $this->error('CURL Error occurred when making request to VHT:' . $error);
            }

            if ($code != 200) {
                $this->error('VHT CURL response code:' . $code);
            }

            $json = json_decode($response, true);
            if ( ! $json) {
                $this->error('Unable to decode JSON response from VHT');
            }

            $smsData = $json['submission']['sms'][0];
            if ( ! isset($smsData)) {
                $this->error('Unable to get data from VHT response');
            }

            $status = $smsData['status'];
            $error  = $smsData['error_message'];

            if ((int)$status === 0) {
                $this->infoLog("Phone number: $number. SMS was successfully sent");

                return true;
            }

            $this->error("Phone number: $number\n$error");

            return false;

        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
            return false;
        }
    }

    /**
     * @param string $number
     *
     * @return string
     */
    protected function normalizeNumber(string $number): string
    {
        if ((strlen($number) == 9 || strlen($number) == 10) && $number[0] != '0' && substr($number, 0, 2) != '84') {
            $number = '84' . $number;
        }

        return preg_replace("/^\+/", '', $number);
    }
}
