<?php

return [
    'class' => \yii\elasticsearch\Connection::class,
    'autodetectCluster' => false,
    'connectionTimeout' => 2,
    'dataTimeout' => 2,
    'nodes' => [
        ['http_address' => getenv('elastic_url')],
        // configure more hosts if you have a cluster
    ],
];
