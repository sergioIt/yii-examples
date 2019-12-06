<?php

return [
    'class'        => 'yii\db\Connection',
    'dsn'          =>'pgsql:host='.getenv('crm_host').';port='.getenv('crm_port').';dbname='.getenv('crm_db'),
    'username'     => getenv('crm_user'),
    'password'     => getenv('crm_password'),
    'charset'      => 'utf8',
    'on afterOpen' => function ($event) {
        $event->sender->createCommand("SET timezone='asia/bangkok';")->execute();
    },
    'attributes'   => [
        //PDO::ATTR_PERSISTENT => true,
        //PDO::ATTR_TIMEOUT    => 5,
        //PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => true,
    ],
    'enableSchemaCache' => true,
    'enableLogging' => false,
    'enableProfiling' => false,
];
