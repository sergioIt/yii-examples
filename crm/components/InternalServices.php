<?php


namespace app\components;

/**
 * класс для описания сервисов внтренней сети, с которыми взаимодейстует  crm
 *
 *
 * Class InternalServices
 * @package app\components
 */
class InternalServices
{

    const APP_SERVICE = 'baza';

    const EMAILER_SERVICE = 'emailer';

    const PRIVATE_API_SERVICE = 'private-api';

    const CRM_WEBSOCKET_SERVICE = 'crm005-ws';

    const CRM_WEBSOCKET_SERVICE_PORT = '8082';

}
