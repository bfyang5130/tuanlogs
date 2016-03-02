<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'language'=>'zh-CN',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['debug'],
    'modules' => [
        'debug'=>[
            'class'=>'yii\debug\Module',
            'allowedIPs' => ['*.*.*.*', '127.0.0.1', '::1']
        ],
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'modules' => [
                'rbac' => [
                    'class' => 'yii2mod\rbac\Module',
                    'layout' => 'rbac',
                    //Some controller property maybe need to change. 
                    'controllerMap' => [
                        'assignment' => [
                            'class' => 'yii2mod\rbac\controllers\AssignmentController',
                            'userClassName' => 'common\models\User',
                        ]
                    ]
                ],
            ]
        ],
        'ajax' => [
            'class' => 'backend\modules\ajax\AjaxModule',
        ],
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest', 'user'],
            'cache' => 'yii\caching\FileCache',
            'itemTable' => 'AuthItem',
            'itemChildTable' => 'AuthItemChild',
            'assignmentTable' => 'AuthAssignment',
            'ruleTable' => 'AuthRule',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/themes/base',
                    '@app/modules' => '@app/themes/base/modules',
                    '@app/widgets' => '@app/themes/base/widgets'
                ],
                'baseUrl' => '@web/base',
            ],
        ],
    ],
    'params' => $params,
];
