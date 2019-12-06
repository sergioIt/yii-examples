<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 23.08.2018, 16:47
 */

namespace app\components\api;


use app\models\Customer;
use app\models\SmsLog;
use GuzzleHttp\Client;
use yii\base\Component;
use yii\base\Exception;

/**
 * Class SmsApi
 * @package app\components\api
 *
 * @property string $baseUrl
 * @property float $credit
 */
abstract class SmsApi extends Component
{
    /**
     * Log category
     */
    const LOG_CATEGORY = 'sms';

    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @var string
     */
    public $url;

    /**
     * @inheritdoc
     * @throws \yii\base\Exception
     */
    public function init()
    {
        $this->httpClient = new Client([
            'base_url' => $this->getBaseUrl(),
            'timeout'  => 5.0,
        ]);
    }

    /**
     * Info log
     *
     * @param string $message
     */
    protected function infoLog(string $message)
    {
        \Yii::info($message, self::LOG_CATEGORY);
    }

    /**
     * Error log
     *
     * @param string $message
     */
    protected function errorLog(string $message)
    {
        \Yii::error($message, self::LOG_CATEGORY);
    }

    /**
     * @param string $message
     *
     * @throws Exception
     */
    protected function error(string $message)
    {
        $this->errorLog($message);
        throw new Exception($message);
    }

    /**
     * Returns base api service url
     * @return string
     * @throws \yii\base\Exception
     */
    protected function getBaseUrl(): string
    {
        if ($this->url === null) {
            throw new Exception('API URL not set');
        }

        return $this->url;
    }

    /**
     * @param Customer $customer
     * @param string $message
     * @param string $type
     * @param string $language
     *
     * @return bool
     * @throws \yii\base\Exception
     */
    public function sendToCustomer(Customer $customer, string $message, string $type, string $language = 'en'): bool
    {
        $smsLog = new SmsLog();
        $smsLog->setAttributes([
            'customer_id' => $customer->id,
            'phone'       => $customer->phone,
            'text'        => $message,
            'type'        => $type,
            'language'    => $language,
            'status'      => SmsLog::STATUS_PENDING,
            'service'     => $this->getId(),
        ]);
        $smsLog->save();

        try {
            if (!$this->send($customer->phone, $message)) {
                throw new Exception('Send sms error');
            }
        } catch (\Exception $e) {
            $smsLog->status = SmsLog::STATUS_ERROR;
            $smsLog->system_info = $e->getMessage();
            $smsLog->save();

            $this->errorLog($e->getMessage());
            return false;
        }

        $smsLog->status = SmsLog::STATUS_SUCCESS;
        return $smsLog->save();
    }

    /**
     * Send sms-message
     *
     * @param string $number
     * @param $message
     *
     * @return bool
     */
    abstract public function send(string $number, $message): bool;

    /**
     * Получение идентификатора сервиса
     * @return string
     */
    abstract public function getId(): string;
}
