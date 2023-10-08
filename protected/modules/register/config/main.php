<?php

$config = [
    'components' => [
        'producer' => [
            'class' => 'app\components\WebUser',
            'identityClass' => 'app\models\AuthProducer',
            'enableAutoLogin' => true,
            'loginUrl' => ['register/default/login']
        ],
    ],
];

return $config;
