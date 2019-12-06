<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use app\helpers\param\EnvParam;
use app\helpers\param\ExternalCallEngineParam;
use app\helpers\RbacHelper;
use app\models\CallsIncomingData;
use app\models\Support;
use Yii;
use yii\base\InvalidArgumentException;
use yii\bootstrap\BootstrapAsset;
use yii\helpers\Url;
use yii\web\AssetBundle;
use yii\web\View;
use yii\web\YiiAsset;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
        'js/app.js',
        'js/web-socket.js',
        'js/cards.js',
        'js/customer.js',
        'js/tasks.js',
        'js/checker.js',
        'js/google_chart.js',
    ];

    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        FileUploadAsset::class,
        TableSorterAsset::class,
        BootstrapModalAsset::class,
    ];

    /**
     * Set application parameters in js
     * @param View $view
     * @return AssetBundle
     * @throws InvalidArgumentException
     */
    public static function register($view): AssetBundle
    {
        $user = Support::getCurrent();

        $app = [
            'params' => [
                'menu' => Yii::$app->params['crm']['menu'],
                'adminEmail' => Yii::$app->params['crm']['adminEmail'],
                'webSocketAddress' => EnvParam::webSocketUrl(),
                'call_socket_url' => ExternalCallEngineParam::listenerUrl(), // url сокета для прослушивания входящих звонков
                'base_url' => Url::base(true)
            ],
            'user' => [
                'id' => $user ? $user->getId() : false,
                'login' => $user ? $user->getLogin() : false,
                'roles' => $user
                    ? array_values(array_map(function ($role) {
                        return $role->name;
                    }, Yii::$app->authManager->getRolesByUser($user->getId())))
                    : false,
                // это значение параметра используется при подключении к веб-сокету
                // возмоные значения: admin - выводятся статусы подключеия для всех юзеров  (в crm  это соответствует ролям super-admin)
                // private - выводится только статус текущего юзера (в crm  это соответствует ролям seller, support)
                'call_engine_socket_level' => RbacHelper::callEngineSocketLevel(),
                'inactivity_timeout'    => Yii::$app->params['crm']['inactivity_timeout'],
            ],
            'users' => Support::find()->active()->indexBy('id')->select('login')->cache(60*60)->column(),
            'ivr_types' => CallsIncomingData::getTypes(),
        ];

        $view->registerJsVar('app', $app);

        if(RbacHelper::callEngineSocketLevel() === 'private'){

            CallEngineSocketOnlineStatusPersonalAsset::register($view);
        }

        return parent::register($view);
    }
}
