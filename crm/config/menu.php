<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 02.08.17
 * Time: 17:16
 *
 * all possible menu items
 */


use app\helpers\Menu;
use app\helpers\RbacHelper;

return [

    'taskman' => [
        'label' => \app\helpers\HtmlHelper::gliphicon('tasks', Yii::t('app','Menu.TaskMan')),
        'items' => [
            [
                'label' => 'All tasks (archived)',
                'url' => '/task/list',
                'role' =>  RbacHelper::ROLE_SUPER_ADMIN,
            ],
        ],
    ],

    'customers' => [
        'label' => Yii::t('app','Menu.Customers'),
        'items' => [
            ['label' => 'List synced',
                'url' => '/customer/list-synced',
                'role' => RbacHelper::ROLE_SUPER_ADMIN,
            ]
        ],
    ],
    'user' => [
        'label' =>  \app\helpers\HtmlHelper::gliphicon('user', Yii::t('app','Menu.User')),
        'items' => [
            [
                'label' =>  Yii::t('app','Menu.User.All'), 'url' => '/user/list'
            ],
            [
                'label' =>  'Asterisk users', 'url' => '/user/list-asterisk'
            ],
        ],
        'role' => [RbacHelper::ROLE_SUPER_ADMIN]
    ],
    'call' => [
        'label' => \app\helpers\HtmlHelper::gliphicon('earphone',Yii::t('app','Menu.Call')),
        'items' => [
            [
                'label' =>  Yii::t('app','Menu.Call.TimeLine'),
                'url' => '/call/timeline',
                'role' => 'call.timeline'
            ],
            [
                'label' =>  Yii::t('app','Menu.Call.List'),
                'url' => '/call/list',
                'role' => [
                    RbacHelper::ROLE_SUPER_ADMIN,
                    RbacHelper::ROLE_ADMIN,
                ],
                'enabled' => RbacHelper::userAllowCallList()
            ],

        ],
    ],

    'settings' => [
        'label' => \app\helpers\HtmlHelper::gliphicon('cog', Yii::t('app','Menu.Settings')),
        'items' => [
            [
                'label' => 'Queue manager',
                'url' => '/queue/manager',
                'role' => RbacHelper::ROLE_SUPER_ADMIN

            ],
        ],
        'visible' => RbacHelper::can(RbacHelper::ROLE_SUPER_ADMIN),
    ],
    'stat' =>
        [
            'label' => \app\helpers\HtmlHelper::gliphicon('stats', Yii::t('app','Menu.Statistics')),
            'items' => [
                ['label' => Yii::t('app','Menu.Cards.ActionsMyList'),
                    'url' => '/card-actions/list-my',
                    'role' => 'card.list-actions-my'
                ],
                ['label' => Yii::t('app','Menu.Cards.Actions'),
                    'url' => '/card-actions/list',
                    'role' => 'card.list-actions'
                ],
                [
                    'label' =>  Yii::t('app','Menu.Statistics.SupportActionsPersonal'),
                    'url' => '/seller-statistics/actions-personal',
                    'role' => 'stat.seller.actions-personal'
                ],
                [
                    'label' =>  Yii::t('app','Menu.Statistics.Sellers.ActionsSummary'),
                    'url' => '/seller-statistics/actions-all',
                    'role' => 'stat.seller.actions-all'
                ],
                [
                    'label' =>  Yii::t('app','Menu.Statistics.Sellers.ActionsDaily'),
                    'url' => '/seller-statistics/actions-daily',
                    'role' => 'stat.seller.actions-daily'
                ],
                [
                    'label' =>  Yii::t('app','Menu.Statistics.ActionsTotal'),
                    'url' => '/seller-statistics/actions-total',
                    'role' => [RbacHelper::ROLE_SUPER_ADMIN],
                ],
                [
                    'label' =>  Yii::t('app','Menu.Statistics.Verification.ByDay'),
                    'url' => '/verification-statistics/by-day',
                    'role' => 'stat.verification.by-day'
                ],
                [
                    'label' =>  Yii::t('app','Menu.Statistics.Verification.ByAdmin'),
                    'url' => '/verification-statistics/by-admin',
                    'role' => 'stat.verification.by-admin'
                ],
                [
                    'label' =>  Yii::t('app','Menu.Statistics.Verification.ActionsDaily'),
                    'url' => '/verification-statistics/actions-daily',
                    'role' => 'stat.verification.actions-daily'
                ],
                [
                    'label' =>  Yii::t('app','Menu.Statistics.Verification.ActionsList'),
                    'url' => '/verification-statistics/actions-list',
                    'role' => 'stat.verification.actions-list'
                ],
                [
                    'label' =>  Yii::t('app','Menu.Statistics.PaymentVerification.StatusChangesList'),
                    'url' => '/payment-verification-statistics/status-changes-list',
                    'role' => [
                        RbacHelper::ROLE_SUPER_ADMIN,
                    ],
                ],
                [
                    'label' =>  Yii::t('app','Menu.Statistics.PaymentVerification.StatusChangesByDate'),
                    'url' => '/payment-verification-statistics/status-changes-by-date',
                    'role' => [
                        RbacHelper::ROLE_SUPER_ADMIN,
                    ],
                ],
                [
                    'label' =>  Yii::t('app','Menu.Statistics.PaymentVerification.StatusChangesByAdmin'),
                    'url' => '/payment-verification-statistics/status-changes-by-admin',
                    'role' => [
                        RbacHelper::ROLE_SUPER_ADMIN,
                    ],
                ],
                [
                    'label' =>  'CCS: Service level (SL)',
                    'url' => ['//support-statistics/service-level'],
                    'role' => [RbacHelper::ROLE_SUPER_ADMIN, RbacHelper::ROLE_ADMIN],
                ],
                [
                    'label' =>  'CCS: Abandonment rate (AR)',
                    'url' => ['//support-statistics/abandonment-rate'],
                    'role' => [RbacHelper::ROLE_SUPER_ADMIN, RbacHelper::ROLE_ADMIN],
                ],
                [
                    'label' =>  'CCS: Avg speed of answer (ASA)',
                    'url' => ['//support-statistics/speed-of-answer'],
                    'role' => [RbacHelper::ROLE_SUPER_ADMIN, RbacHelper::ROLE_ADMIN],
                ],
                [
                    'label' =>  'CCS: Occupancy & Average handling time (AHT)',
                    'url' => ['//support-statistics/occupancy'],
                    'role' => [RbacHelper::ROLE_SUPER_ADMIN, RbacHelper::ROLE_ADMIN],
                ],
                [
                    'label' =>  'CCS: First call resolution',
                    'url' => ['//support-statistics/full-call-resolution'],
                    'role' => [RbacHelper::ROLE_SUPER_ADMIN, RbacHelper::ROLE_ADMIN],
                ],
                [
                    'label' =>  'Call quality',
                    'url' => ['//support-statistics/call-quality'],
                    'role' => [RbacHelper::ROLE_SUPER_ADMIN, RbacHelper::ROLE_ADMIN],
                ],
                [
                    'label' =>  'Ping stat',
                    'url' => ['//support-statistics/ping'],
                    'role' => [RbacHelper::ROLE_SUPER_ADMIN, RbacHelper::ROLE_ADMIN],
                ],
            ],
        ],
    'system' =>
        [
                   'label' => \app\helpers\HtmlHelper::gliphicon('lock', Yii::t('app','Menu.System')),
            'items' => [
                [
                    'label' =>  Yii::t('app','Menu.System.AuthLog'),
                    'url' => '/system/auth-log',
                    'role' => 'system.auth-log'
                ],
                [
                    'label' =>  Yii::t('app','Menu.System.RbacItems'),
                    'url' => '/rbac/list-items',
                    'role' => RbacHelper::ROLE_SUPER_ADMIN
                ],
                [
                    'label' =>  Yii::t('app','Menu.System.RbacParentChild'),
                    'url' => '/rbac/list-parent-child',
                    'role' => RbacHelper::ROLE_SUPER_ADMIN
                ], [
                    'label' =>  Yii::t('app','Menu.System.RbacAssignment'),
                    'url' => '/rbac/list-assignment',
                    'role' => RbacHelper::ROLE_SUPER_ADMIN
                ],
                [
                    'label' =>  Yii::t('app','Menu.System.TransitsManagerLog'),
                    'url' => '/system/transits-manager-log',
                    'role' => 'system.log-transit-manager'
                ],
                [
                    'label' => 'DB statistic',
                    'url'   => '/system/db-statistic',
                    'role'  => 'system.app-log',
                ],
            ],
        ],
    'tools' => [
        'label' => \app\helpers\HtmlHelper::gliphicon('wrench',Yii::t('app', 'Menu.Tools')),
        'items' => [
           ],
    ],
    'asterisk' => [
        'label' => \app\helpers\HtmlHelper::gliphicon('transfer', 'Asterisk'),

        'items' => [
            [
                'label' => 'Queue manager',
                'url' => '/asterisk-queue',
                'role' => [
                    RbacHelper::ROLE_SUPER_ADMIN,
                ],
            ],
            [
                'label' => 'Phone manager',
                'url' => '/asterisk-phone',
                'role' => [
                    RbacHelper::ROLE_SUPER_ADMIN,
                ],
            ],
            [
                'label' => 'Task manager',
                'url' => '/asterisk-tasks',
                'role' => [
                    RbacHelper::ROLE_SUPER_ADMIN,
                ],
            ],
            [
                'label' => 'Autocall manager',
                'url' => '/asterisk-queue/manager',
                'role' => [
                    RbacHelper::ROLE_SUPER_ADMIN,
                ],
            ],
        ]
    ],

    'language' =>[
        'label' => \app\helpers\HtmlHelper::gliphicon('flag', Yii::t('app','Menu.Language')) .' (' .Yii::$app->language .')',
        'items' => Menu::getLanguageItems(),
    ],

];
