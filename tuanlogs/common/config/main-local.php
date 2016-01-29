<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.100.1.207;dbname=Tuandai_Log',
            'username' => 'root',
            'password' => 'vA3-])A,R!N.NN',
            'charset' => 'utf8'
        ],
//        'db' => [
//            'class' => 'yii\db\Connection',
//            'dsn' => 'mysql:host=192.168.8.188;dbname=logs',
//            'username' => 'root',
//            'password' => 'a888888a',
//            'charset' => 'utf8'
//        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
