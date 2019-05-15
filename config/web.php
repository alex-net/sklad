<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language'=>'ru',
    'name'=>'Энергоучёт. Склад',
    // разлогиниваем юзера  если  его сделали неактивным ..
    'on '.\yii\web\Application::EVENT_BEFORE_REQUEST=>function(){
        if (!Yii::$app->user->isGuest && !Yii::$app->user->identity->status)
            Yii::$app->user->logout();
    },
    //'defaultRoute'=>'site/login',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'imXS36_Jo2U7Kndbu01som62Zwt3WmR3',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\SiteUser',
            'enableAutoLogin' => true,
            'returnUrl'=>['cabinet/index'],
            // ловим вход юзера
            'on '.\yii\web\User::EVENT_AFTER_LOGIN=>function($e){
                $e->identity->setLoggindata();
            },
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'login'=>'site/login',
                'exit'=>'cabinet/logout',
                'user'=>'cabinet/index',
                'user/pass'=>'cabinet/pass',
                'user/edit'=>'cabinet/edit',

                'users'=>'user-man/index',
                'users/<uid:\d+>'=>'user-man/edit',
                'users/new'=>'user-man/edit',


            ],
        ],
        'authManager'=>[
            'class'=>'yii\rbac\PhpManager',
        ],
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1','188.16.56.118'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
