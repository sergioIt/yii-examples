<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 01/07/2019, 16:31
 */

namespace tests\mock\sdk;

use Jupiter\BinaryPlatform\Sdk\Resources\UsersResource;

/**
 * Class UserResource
 * @package tests\mock\sdk
 */
class UserResource extends UsersResource
{
    /**
     * @param string $userKey
     * @param string $provider
     * @param string|null $sub7
     * @param string|null $sub8
     * @param string|null $sub9
     *
     * @return bool
     */
    public function rewriteSubs(
        string $userKey,
        string $provider,
        string $sub7 = null,
        string $sub8 = null,
        string $sub9 = null
    ): bool {
        return true;
    }

    /**
     * @param string $userKey
     *
     * @return string
     */
    public function getAuthToken(string $userKey): string
    {
        return $userKey . '-token';
    }

    /**
     * @param array $usersIds
     * @param string $event
     * @param integer $testCase
     *
     * @return array
     */
    public function getPromoCodes(array $usersIds, string $event, int $testCase = null): array
    {
        switch ($usersIds[0]) {
            case 1:
                return ['status' => 'ERROR', 'message' => 'Test case return error'];
            case 2:
                return ['promo' => 'Incorrect response format'];
            case 3:
                return [3 => ['promo_code' => 'ABCDE', 'promo_code_expiration_date' => time() - 60 * 60]]; // expired
            case 4:
                return [4 => ['promo_code' => 'ABCDE', 'promo_code_expiration_date' => time() + 60 * 60]];

            default:
                return [];
        }
    }

    /**
     * @param int $userId
     *
     * @return bool
     */
    public function resetPassword(int $userId): bool
    {
        return true;
    }

    /**
     * @param int $userId
     * @param bool $active
     *
     * @return bool
     */
    public function activate(int $userId, bool $active): bool
    {
        return true;
    }
}
