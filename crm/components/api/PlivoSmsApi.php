<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 28.08.2018, 13:08
 */

namespace app\components\api;

use Plivo\Resources\Message\MessageCreateResponse;
use Plivo\RestClient;
use yii\helpers\Json;

/**
 * Class PlivoSmsApi
 * @package app\components\api
 */
class PlivoSmsApi extends SmsApi
{
    /**
     * @var string
     */
    public $authId;

    /**
     * @var string
     */
    public $authToken;

    /**
     * @var string
     */
    public $fromName = '0901800086';

    /**
     * @var RestClient
     */
    protected $client;

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'plivo';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->client = new RestClient($this->authId, $this->authToken);
    }

    /**
     * Send sms-message
     *
     * @param string $number
     * @param string $message
     *
     * @return bool
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidArgumentException
     * @throws \Plivo\Exceptions\PlivoValidationException
     *
     * https://api-reference.plivo.com/latest/php/resources/message/send-a-message
     */
    public function send(string $number, $message): bool
    {
        $number  = trim($number);
        $message = trim($message);

        if ( ! $number || ! $message) {
            return false;
        }

        $number = $this->normalizeNumber($number);

        /** @var MessageCreateResponse $response */
        $response = $this->client->messages->create(
            $this->fromName,
            [$number],
            $message
        );

        $this->infoLog('SMS message to ' . $number . ' was successfully sent. Text: ' . $message . '. Debug: ' . Json::encode($response));

        return true;
    }

    /**
     * @param $number
     *
     * @return string
     */
    protected function normalizeNumber($number): string
    {
        $number = preg_replace("/\s+/", '', $number);
        if ($number[0] == '0') { // TODO country VN?
            $number = '84' . substr(substr($number, 1), -10);
        }

        return $number;
    }
}
