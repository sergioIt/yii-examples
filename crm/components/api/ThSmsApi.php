<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 23.08.2018, 16:36
 */

namespace app\components\api;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class ThSmsApi
 * @package app\components\api
 *
 * @property float $credit
 */
class ThSmsApi extends SmsApi
{
    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'thsms';
    }

    /**
     * Get credit value
     * @return float
     * @throws \RuntimeException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidArgumentException
     */
    public function getCredit(): float
    {
        $response = $this->request('credit');

        if ((string)$response->credit->status !== 'success') {
            $this->error('Method: send. Response status from API: ' . (string)$response->credit->status . '. Message: ' . (string)$response->credit->message);
        }

        return (float)$response->credit->amount;
    }

    /**
     * Send sms-message
     *
     * @param string $number
     * @param string $message
     * @param string $from
     *
     * @return bool
     * @throws \yii\base\InvalidArgumentException
     * @throws \yii\base\Exception
     * @throws \RuntimeException
     */
    public function send(string $number, $message, $from = '0000'): bool
    {
        $number  = trim($number);
        $message = trim($message);

        if ( ! $number || ! $message) {
            return false;
        }

        $response = $this->request('send', [
            'from'    => $from,
            'to'      => $number,
            'message' => $message,
        ]);

        if ((string)$response->send->status !== 'success') {
            $this->errorLog('Method: send. Response status from API: ' . (string)$response->send->status . '. Message: ' . (string)$response->send->message);

            return false;
        }

        $this->infoLog('SMS message to ' . $number . ' was successfully sent (' . (string)$response->send->uuid . '). Text: ' . $message . '.');

        return true;
    }

    /**
     * Send request to api
     *
     * @param string $method
     * @param array $params
     *
     * @return \SimpleXMLElement
     * @throws \RuntimeException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidArgumentException
     */
    protected function request(string $method, array $params = []): \SimpleXMLElement
    {
        try {
            $queryParams = ArrayHelper::merge([
                'method'   => $method,
                'username' => $this->username,
                'password' => $this->password,
            ], $params);

            $response   = $this->httpClient->post($this->getBaseUrl(), ['form_params' => $queryParams]);
            $statusCode = $response->getStatusCode();

            if ($statusCode !== 200) {
                $this->error('API returns ' . $statusCode . ' status code. Params: ' . Json::encode($params));
            }

            $result = $response->getBody()->getContents();
            $xml    = simplexml_load_string($result);

            if ( ! is_object($xml)) {
                $this->error('Respond error');
            }

            return $xml;

        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
    }
}
