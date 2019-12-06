<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 05.04.2018, 13:17
 */

namespace app\components;

use app\services\CustomerModalService;
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\web\JqueryAsset;

/**
 * Class CustomerModal
 * @package app\components
 */
class CustomerModal extends Modal
{
    const TYPE_VIEW = 'view';
    const TYPE_OPERATIONS_REAL = 'operations_real';
    const TYPE_OPERATIONS_DEMO = 'operations_demo';
    const TYPE_OPERATIONS_ARCHIVE = 'operations_archive';
    const TYPE_OPERATIONS_TOURNAMENT = 'operations_tournament';
    const TYPE_PAYMENTS = 'payments';
    const TYPE_WITHDRAW = 'withdraw';
    const TYPE_REFERRALS = 'referrals';
    const TYPE_BALANCE_HISTORY = 'balance_history';
    const TYPE_CALL_HISTORY = 'call_history';
    const TYPE_SEND_MAIL = 'send_mail';
    const TYPE_TOURNAMENTS = 'tournaments';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->getView()->registerJsFile('js/customer-modal.js?' . App::getVersion(), ['depends' => JqueryAsset::class]);
        parent::init();
    }

    /**
     * Кастомная кнопка закрытия модалки - большая и красная
     *
     * @return string
     */
    protected function renderCloseButton()
    {
        $icon = \yii\bootstrap\Html::icon('remove', [
            'style' => 'color:red; font-size:1.5em;'
        ]);

        return Html::button($icon, ['class' => 'close', 'data-dismiss' => 'modal', ' aria-hidden' => true]);
    }

    /**
     * Return content for modal
     * @return string
     * @throws \Exception
     */
    public static function getContent(): string
    {
        $filtered = self::getTabsConfig();

        return Tabs::widget($filtered);
    }

    /**
     * Config for use in bootstrap tabs widget
     * @see \yii\bootstrap\Tabs::widget()
     * @return array
     */
    protected static function getTabsConfig(): array
    {
        return [
            'options' => ['class' => 'customer-modal-tabs'],
            'encodeLabels' => false,
            'items'       => [
                [
                    'label'       => \Yii::t('app', 'Card.View.Button.Main'),
                    'linkOptions' => ['data-url' => '/customer/view', 'data-type' => self::TYPE_VIEW],
                    'options'     => ['class' => 'tab-content-customer-view'],
                ],
                [
                    'label' => \Yii::t('app', 'Card.View.Button.OperationsTab'),
                    'items' => [
                        [
                            'label'          => \Yii::t('app', 'Card.View.Button.OperationsReal'),
                            'options'        => ['class' => 'check-tab'],
                            'linkOptions'    => [
                                'data-url'  => '/customer/operations?mode=1',
                                'data-type' => self::TYPE_OPERATIONS_REAL,
                                'class' => 'operations-real-link'
                            ],
                            'contentOptions' => ['class' => 'tab-content-operations-real', 'style' => 'padding: 10px'],
                            'content'        => '',
                            'visible'        => CustomerModalService::userAllow(self::TYPE_OPERATIONS_REAL),
                        ],
                        [
                            'label'          => \Yii::t('app', 'Card.View.Button.OperationsDemo'),
                            'options'        => ['class' => 'check-tab'],
                            'linkOptions'    => [
                                'data-url'  => '/customer/operations',
                                'data-type' => self::TYPE_OPERATIONS_DEMO,
                                'class' => 'operations-demo-link'
                            ],
                            'contentOptions' => ['class' => 'tab-content-operations-demo', 'style' => 'padding: 10px'],
                            'content'        => '',
                            'visible'        => CustomerModalService::userAllow(self::TYPE_OPERATIONS_DEMO),
                        ],
                        [
                            'label'          => \Yii::t('app', 'Card.View.Button.OperationsArchive'),
                            'options'        => ['class' => 'check-tab'],
                            'linkOptions'    => [
                                'data-url'  => '/customer/archive-operations',
                                'data-type' => self::TYPE_OPERATIONS_ARCHIVE,
                                'class' => 'operations-archive-link'
                            ],
                            'contentOptions' => ['class' => 'tab-content-operations-archive', 'style' => 'padding: 10px'],
                            'content'        => '',
                            'visible'        => CustomerModalService::userAllow(self::TYPE_OPERATIONS_ARCHIVE),
                        ],
                        [
                            'label'          => \Yii::t('app', 'Card.View.Button.OperationsTournament'),
                            'options'        => ['class' => 'check-tab'],
                            'linkOptions'    => [
                                'data-url'  => '/customer/tournament-operations',
                                'data-type' => self::TYPE_OPERATIONS_TOURNAMENT,
                                'class' => 'operations-tournament-link'
                            ],
                            'contentOptions' => ['class' => 'tab-content-operations-tournament', 'style' => 'padding: 10px'],
                            'content'        => '',
                            'visible'        =>  CustomerModalService::userAllow(self::TYPE_OPERATIONS_TOURNAMENT),
                        ],
                    ],
                    'linkOptions' => ['class' => 'operations-dropdown-link'],
                ],
                [
                    'label'         => \Yii::t('app', 'Card.View.Button.Payments'),
                    'headerOptions' => ['class' => 'check-tab'],
                    'linkOptions'   => [
                        'data-url' => '/customer/payments', '
                        data-type' => self::TYPE_PAYMENTS,
                        'class'    => 'payments-link'

                    ],
                    'options'       => ['class' => 'tab-content-payments'],
                    'visible'        => CustomerModalService::userAllow(self::TYPE_PAYMENTS),
                ],
                [
                    'label'         => \Yii::t('app', 'Card.View.Button.Withdraw'),
                    'headerOptions' => ['class' => 'check-tab'],
                    'linkOptions'   => [
                        'data-url' => '/customer/withdraw',
                        'data-type' => self::TYPE_WITHDRAW,
                        'class'    => 'withdrawals-link'
                    ],
                    'options'       => ['class' => 'tab-content-withdraw'],
                    'visible'        => CustomerModalService::userAllow(self::TYPE_WITHDRAW),
                ],
                [
                    'label'         => \Yii::t('app', 'Card.View.Button.Referrals'),
                    'headerOptions' => ['class' => 'check-tab'],
                    'linkOptions'   => [
                        'data-url' => '/customer/referrals',
                        'data-type' => self::TYPE_REFERRALS,
                        'class'    => 'referrals-link'

                    ],
                    'options'       => ['class' => 'tab-content-referrals'],
                    'visible'        =>  CustomerModalService::userAllow(self::TYPE_REFERRALS),
                ],
                [
                    'label'         => \Yii::t('app', 'Card.View.Button.BalanceHistory'),
                    'headerOptions' => ['class' => 'check-tab'],
                    'linkOptions'   => [
                        'data-url'  => '/customer/balance-history',
                        'data-type' => self::TYPE_BALANCE_HISTORY,
                        'class'    => 'balance-history-link'
                    ],
                    'options'       => ['class' => 'tab-content-balance-history'],
                    'visible'        => CustomerModalService::userAllow(self::TYPE_BALANCE_HISTORY),
                ],
                [
                    'label'         => \Yii::t('app', 'Card.View.Button.Calls'),
                    'headerOptions' => ['class' => 'check-tab'],
                    'linkOptions'   => [
                        'data-url'  => '/customer/call-history',
                        'data-type' => self::TYPE_CALL_HISTORY,
                        'class'    => 'call-history-link'
                    ],
                    'options'       => ['class' => 'tab-content-call-history'],
                    'visible' =>  CustomerModalService::userAllow(self::TYPE_CALL_HISTORY),
                ],
                [
                    'label'         => 'mail / sms',
                    'headerOptions' => ['class' => 'check-tab'],
                    'linkOptions'   => [
                        'data-url'  => '/customer/send-mail',
                        'data-type' => self::TYPE_SEND_MAIL,
                        'class'    => 'mail-link'
                    ],
                    'options'       => ['id' => 'tab-content-send-mail'],
                    'visible'       => CustomerModalService::userAllow(self::TYPE_SEND_MAIL),
                ],
                [
                    'label'         => 'tournaments',
                    'linkOptions'   => [
                        'data-url'  => '/customer/tournaments',
                        'data-type' => self::TYPE_TOURNAMENTS,
                        'class'    => 'tournaments-link'
                    ],
                    'options'       => ['id' => 'tab-content-tournaments'],
                    'visible'       => CustomerModalService::userAllow(self::TYPE_TOURNAMENTS),
                ],
            ],
            'linkOptions' => [
                'style' => 'font-size: 12px; font-weight: bold;',
            ],
            'itemOptions' => [
                'style' => 'padding: 10px',
            ],
        ];
    }

}
