<?php

use yii\helpers\Url;


$params = require(__DIR__ . '/params.php');
setlocale(LC_TIME, 'ru_RU.UTF-8');
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' =>'ru',
    'name' => 'Konkurs',

    'aliases' => [
//        Yii::setAlias('phpbb', dirname(dirname(__DIR__)) . '/forum/phpbb')
    ],
    'components' => [
//        'phpBB' => [
//            'class' => 'nill\forum\phpBB',
//            'path' => $_SERVER['DOCUMENT_ROOT']. '\forum',
////            'path' => '@app/forum',
//        ],
        'formatter' => [
            'locale'=>'ru_RU.UTF-8'
        ],
        'eauth' => array(
            'class' => 'nodge\eauth\EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
            'httpClient' => array(
                // uncomment this to use streams in safe_mode
                //'useStreamsFallback' => true,
            ),
            'services' => array( // You can change the providers and their classes.

                'facebook' => array(
                    // register your app here: https://developers.facebook.com/apps/
                    'class' => 'nodge\eauth\services\FacebookOAuth2Service',
                    'clientId' => '1439319926364095',
                    'clientSecret' => '009220b036a5a0f54337ec147e08a655',
                ),

                'vkontakte' => array(
                    // register your app here: https://vk.com/editapp?act=create&site=1
                    'class' => 'nodge\eauth\services\VKontakteOAuth2Service',
                    'clientId' => '4893908',
                    'clientSecret' => 'dWe3g8i4PKpa1GR1J5QN',
                ),
            ),
        ),
        'paypal'=> [
            'class'        => 'marciocamello\Paypal',
            'clientId'     => 'lexam85_api1.gmail.com',
            'clientSecret' => 'YVAGFD5L5FUH97KY',
            'isProduction' => false,
            // This is config file for the PayPal system
            'config'       => [
                'http.ConnectionTimeOut' => 30,
                'http.Retry'             => 1,
                'mode'                   => 'sandbox', // development (sandbox) or production (live) mode
                'log.LogEnabled'         => YII_DEBUG ? 1 : 0,
                'log.FileName'           => '@runtime/logs/paypal.log',
//                'log.LogLevel'           => Paypal::LOG_LEVEL_FINE,
            ]
        ],
        'robokassa' => [
            'class' => '\robokassa\Merchant',
            'baseUrl' => YII_ENV_PROD ? 'https://auth.robokassa.ru/Merchant/Index.aspx' : 'http://test.robokassa.ru/Index.aspx',
            'sMerchantLogin' => 'work_portal',
            'sMerchantPass1' => 'lexam85',
            'sMerchantPass2' => 'utF6T7w3dM',
        ],

        'i18n' => array(
            'translations' => array(
                'eauth' => array(
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@eauth/messages',
                ),
            ),
        ),
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'a7pVRjygzcZD3UpFaYTHwaUKcPTYC4N1',
            'baseUrl' => $_SERVER['DOCUMENT_ROOT'] . $_SERVER['PHP_SELF'] != $_SERVER['SCRIPT_FILENAME'] ? 'http://' . $_SERVER['HTTP_HOST'] : '',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
//            'class' => 'nill\forum\PhpBBWebUser',
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
//            'on afterLogin' => function($event)
//            {
////                var_dump($event->identity->password_reg);
////                var_dump($event->identity->name);
////                die();
////                $phpbb = new \nill\forum\behaviors\PhpBBUserBahavior();
////                $phpbb->Login($event->identity->name,$event->identity->password_reg);
//                Yii::$app->phpBB->login($event->identity->name,$event->identity->password_reg);
////                \nill\forum\behaviors\PhpBBUserBahavior::className()
//
//            },
//            'on afterLogout' => function($event)
//            {
////                var_dump($event->identity->password_reg);
////                var_dump($event->identity->name);
////                die();
////                $phpbb = new \nill\forum\behaviors\PhpBBUserBahavior();
////                $phpbb->Logout();
//                Yii::$app->phpBB->logout();
////                \nill\forum\behaviors\PhpBBUserBahavior::className()
//
//            }
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
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
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['admin', 'worker', 'employer'], // Здесь нет роли "guest", т.к. эта роль виртуальная и не присутствует в модели UserExt
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];
require(__DIR__ . '/dev.php');

return $config;
