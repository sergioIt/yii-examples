<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 01/07/2019, 16:19
 */

namespace tests\mock\sdk;

use GuzzleHttp\Client;
use Jupiter\BinaryPlatform\Sdk\Config;

/**
 * Class BinaryPlatformClient
 * @package tests\mock
 */
class BinaryPlatformClient extends \Jupiter\BinaryPlatform\Sdk\BinaryPlatformClient
{
    /**
     * BinaryPlatformClient constructor.
     *
     * @param Config $config
     * @param Client|null $client
     */
    public function __construct(Config $config, Client $client = null)
    {
        if ($client === null) {
            $client = new Client([
                'http_errors' => false
            ]);
        }

        $this->users = new UserResource($config, $client);
    }

}
