<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 19/11/2018, 16:38
 */

namespace app\daemons;


use app\services\CustomersService;
use vakata\websocket\Server;
use yii\helpers\Json;

/**
 * Class WebSocketServer
 * @package app\daemons
 */
class WebSocketServer extends Server
{
    const LOG_CATEGORY = 'web-socket-server';

    public $clients = [];

    CONST LOCAL_URL = 'ws://0.0.0.0:8082';
    /**
     * WebSocketServer constructor.
     *
     * @param null $cert
     * @param null $pass
     * @throws \vakata\websocket\WebSocketException
     */
    public function __construct($cert = null, $pass = null)
    {
        parent::__construct(self::LOCAL_URL, $cert, $pass);
        $this->init();
    }

    /**
     * Initial method
     */
    public function init()
    {
        $this->onMessage([$this, 'message']);
    }

    /**
     * @param resource $socket
     * @param string $data
     * @param string $opcode
     * @param bool $masked
     *
     * @return bool
     */
    public function send(&$socket, $data, $opcode = 'text', $masked = false): bool
    {
        try {
            $result = parent::send($socket, $data, $opcode, $masked);
        } catch (\Throwable $e) {
            \Yii::warning($e->getMessage(), self::LOG_CATEGORY);
            return false;
        }

        return $result;
    }

    /**
     * Processing message
     * Must be JSON : {action: 'action', data: []}
     *
     * @param $sender
     * @param $message
     * @param $server
     *
     * @throws \yii\base\InvalidArgumentException
     */
    protected function message($sender, $message, $server)
    {
        $message = json_decode($message, true);

        if ( ! empty($message['action']) && method_exists($this, $message['action'])) {
            $data = $message['data'] ?? [];
            $this->{$message['action']}($data, $sender, $server);
        }
    }

    /**
     * @param array $data
     * @param array $sender
     */
    public function setUserId(array $data, array $sender)
    {
        foreach ($this->getClients() as $key => $client) {
            if ((int)$sender['socket'] === (int)$client['socket']) {
                $client['id']        = $data['id'];
                $this->clients[$key] = $client;

                break;
            }
        }
    }

    /**
     * @param array $data
     * @param array $sender
     *
     * @throws \yii\base\InvalidArgumentException
     */
    public function getAllClients(array $data, array $sender)
    {
        $clients = [];
        foreach ($this->getClients() as $key => $client) {
            $clients[] = [
                'id'      => array_key_exists('id', $client) ? $client['id'] : 0,
                'socket'  => (int)$client['socket'],
                'headers' => $client['headers'],
            ];
        }

        $this->send($sender['socket'], Json::encode(['action' => 'clients', 'data' => $clients]));
    }

    /**
     * Get clients by id (can be few tabs in browser)
     * @param int $id
     *
     * @return array
     */
    protected function getClientById(int $id): array
    {
        $clients = [];
        foreach ($this->getClients() as $key => $client) {
            if (array_key_exists('id', $client) && (int)$client['id'] === $id) {
                $clients[] = $client;
            }
        }

        return $clients;
    }

    /**
     * @param array $data
     * @param array $sender
     */
    public function getOnlineCustomers(array $data, array $sender) {

        $service = new CustomersService();

        $result = [];

        foreach ($data as $customerId){

            $result[$customerId] = $service->getIsOnline($customerId);
        }

        $this->send($sender['socket'], Json::encode(['action' => 'onlineCustomers', 'data' => $result]));
    }

}
