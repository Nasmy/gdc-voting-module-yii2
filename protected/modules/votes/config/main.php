<?php

$config = [
    'components' => [
        'user' => [
            'class' => 'app\components\WebUser',
            'identityClass' => 'app\models\AuthVoter',
            'enableAutoLogin' => true,
            'loginUrl' => ['votes/default/login']
        ],
    ],
];

return $config;
