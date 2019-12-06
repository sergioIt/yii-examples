<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 24.08.2018, 13:34
 */

namespace app\services;

use app\exceptions\UserServiceException;
use app\helpers\param\ExternalAppParam;
use app\models\crm\TournamentInvitationLog;
use app\models\Customers;
use Jupiter\BinaryPlatform\Sdk\BinaryPlatformClient;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

/**
 * У нас еще есть CustomerService, что по смыслу - об одном и том же (о кастомерах)
 * Но этот сервис создавался в контексте работы с клиентами из основного проекта, а они там - users
 *
 * Будем рассматривать его для работы с основным проектом
 *  (получением данных по api для пользователей, например)
 *
 * @package app\services
 */
class UserService extends Component
{
    /**
     * @var BinaryPlatformClient
     */
    protected $client;

    /**
     * Используется dependency injection container,
     *  передавать в конструктор ничего не надо,
     *  просто создание класса через \Yii::createObject() или \Yii::$container->get()
     *
     * @param BinaryPlatformClient $client
     * @param array $config
     */
    public function __construct(BinaryPlatformClient $client, $config = [])
    {
        $this->client = $client;
        parent::__construct($config);
    }

    /**
     * @param string $userKey
     *
     * @return string
     * @throws \Jupiter\BinaryPlatform\Sdk\Exceptions\RequestException
     * @throws \Jupiter\BinaryPlatform\Sdk\Exceptions\InvalidAnswerFormatException
     */
    public function getAuthToken(string $userKey): string
    {
        return $this->client->users->getAuthToken($userKey);
    }

    /**
     * Generate and return update password link
     *
     * @param string $token
     *
     * @return string
     */
    public function getUpdatePasswordLink(string $token): string
    {
        $externalBaseUrl = ExternalAppParam::getUrl();

        return $externalBaseUrl . '/password-update?token=' . $token;
    }

    /**
     * Получение промокода
     *
     * @param int $userId
     *
     * @return array
     * @throws \app\exceptions\UserServiceException
     * @throws \Jupiter\BinaryPlatform\Sdk\Exceptions\RequestException
     * @throws \Jupiter\BinaryPlatform\Sdk\Exceptions\InvalidAnswerFormatException
     * @throws \RuntimeException
     * @throws \yii\base\InvalidArgumentException
     */
    public function getPromoCode(int $userId): array
    {
        $result = $this->client->users->getPromoCodes([$userId], 'crm_manual_trigger');

        # ожидаем status = OK
        if (array_key_exists('status', $result) && $result['status'] === 'ERROR') {
            throw new UserServiceException('Error get promo code: ' . $result['message']);
        }

        if ( ! array_key_exists($userId, $result) || ! array_key_exists('promo_code', $result[$userId])) {
            throw new UserServiceException('Error API response format');
        }

        # проверим, не истек ли код
        if (time() > $result[$userId]['promo_code_expiration_date']) {
            throw new UserServiceException('Code has expired');
        }

        $code = $result[$userId]['promo_code'];

        return [
            'code'    => $code,
            'percent' => 50,
        ];
    }

    /**
     * Отправляет запрос на api для отправки клиенту приглашения в турнир
     *
     * запрос должен приводить к созданию записи в db2.tournament_availability
     *
     * @param int $userId
     * @throws Exception
     *
     * @return array
     *
     */
    public function sendTournamentInvitation(int $userId){

       $log = TournamentInvitationLog::initPending(\Yii::$app->user->getId(), $userId);

        $result = $this->client->users->inviteTournament($userId);

        # ожидаем status = OK
        if ($result['status'] !== 'OK') {

            $info = (array_key_exists('message', $result)) ? $result['message'] : null;
            // помечаем в логе, что произошла оошибка, и сообщение об ошибке, если есть
            $log->status = TournamentInvitationLog::STATUS_ERROR;
            $log->info = $info;

            if (!$log->save()) {
                throw new Exception('Tournament invitation log save error: ' . Json::encode($log->getErrors()));
            }

            throw new Exception('Error: ' . $result['message']);
        }

        $log->status = TournamentInvitationLog::STATUS_SUCCESS;

        if (!$log->save()) {
            throw new Exception('Tournament invitation log save error: ' . Json::encode($log->getErrors()));
        }

        return $result;

    }

    /**
     * @param array $userIds
     * @param int $status
     *
     * @throws UserServiceException
     *
     * @return array
     */
    public  function setCallCenterStatus(array $userIds, int $status){

        // если нет юзеров, н еотправляем запрос к api, потому что незачем
        if($userIds === []){

            return [];
        }

        $result = $this->client->users->setCallCenterStatus($userIds, $status);

        if (! array_key_exists('status', $result)) {
            throw new UserServiceException('Error set call center status : response not contains status');
        }

        if ($result['status'] === 'ERROR') {
            throw new UserServiceException('Error set call center status : ' . $result['message']);
        }

        if ($result['status'] !== 'OK') {
            throw new UserServiceException('Error set call center status : response status is not ERROR and NOT OK');
        }

        return $result;
    }

    public function verifyContact(int $userId, string $contactType){

        if($contactType !== 'phone' && $contactType !== 'email'){

            throw new UserServiceException('contact type not allowed : '. $contactType);
        }

        $result = [];

        switch ($contactType){

            case 'email':

                $result = $this->client->users->verifyEmail($userId);

                break;

            case 'phone':

                $result = $this->client->users->verifyPhone($userId);

                break;

        }

        if (! array_key_exists('status', $result)) {
            throw new UserServiceException('Error verify email: response from api not contains status');
        }

        if ($result['status'] === 'ERROR') {
            throw new UserServiceException($result['message']);
        }

        if ($result['status'] !== 'OK') {
            throw new UserServiceException('response status is not ERROR and NOT OK');
        }

        return $result;
    }


    /**
     * @param int $customerId
     *
     * @return bool
     * @throws \Jupiter\BinaryPlatform\Sdk\Exceptions\ResponseException
     * @throws \Jupiter\BinaryPlatform\Sdk\Exceptions\RequestException
     * @throws \Jupiter\BinaryPlatform\Sdk\Exceptions\InvalidAnswerFormatException
     * @throws Exception
     */
    public function resetPassword(int $customerId): bool
    {
        if (!$this->client->users->resetPassword($customerId)) {
            throw new Exception('Reset password error');
        }

        return true;
    }

    /**
     * @param int $customerId
     * @param bool $active
     *
     * @return bool
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Jupiter\BinaryPlatform\Sdk\Exceptions\InvalidAnswerFormatException
     * @throws \Jupiter\BinaryPlatform\Sdk\Exceptions\RequestException
     * @throws \Jupiter\BinaryPlatform\Sdk\Exceptions\ResponseException
     * @throws \yii\base\InvalidArgumentException
     */
    public function activate(int $customerId, bool $active): bool
    {
        # должен существовать в нашей БД
        if ( ! $customer = Customers::findOne($customerId)) {
            throw new NotFoundHttpException('Customer #' . $customerId . ' is not found');
        }

        if (!$this->client->users->activate($customerId, $active)) {
            throw new Exception('Active user error');
        }

        # надо обновить поле active и в нашей БД
        $customer->active = (int)$active;
        if ( ! $customer->save()) {
            throw new \Exception('Save customer active status error: ' . Json::encode($customer->getErrors()));
        }

        return true;
    }

    /**
     * Инфомация о турнирах, в которых учавствовал(-ует) пользователь
     *
     * @param string $userKey
     *
     * @param null $limit
     * @param null $offset
     *
     * @return array
     * @throws \Jupiter\BinaryPlatform\Sdk\Exceptions\RequestException
     * @throws \Jupiter\BinaryPlatform\Sdk\Exceptions\InvalidAnswerFormatException
     */
    public function getTournaments(string $userKey, $limit = null, $offset = null): array
    {
        return $this->client->users->tournaments($userKey, $limit, $offset);
    }
}
