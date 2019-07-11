<?php

$params = require(__DIR__ . '/params.php');
setlocale(LC_TIME, 'ru_RU.UTF-8');
$config = [
    'id' => 'basic',
    'name'=>'24 cyber.ru',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'charset' => 'utf-8',
    'timeZone' => 'Europe/Moscow',
    'on beforeAction' => function ($event) {
//         if(!Yii::$app->user->isGuest && Yii::$app->phpBB->loggedin()=="FAIL"){
// //            var_dump($event->identity->username);die();
//             Yii::$app->phpBB->login(Yii::$app->user->identity->username, "", true);
//         }
        //     if(!Yii::$app->request->isSecureConnection || preg_match('/www\./', Yii::$app->request->getAbsoluteUrl())){
        // // add some filter/exemptions if needed ..
        //     $url = Yii::$app->request->getAbsoluteUrl();
        //     $url = str_replace('http://', 'https://', $url);
        //     $url = str_replace('www.', '', $url);
        //     Yii::$app->getResponse()->redirect($url);
        //     Yii::$app->end();
        // }
    },
    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@johnitvn/rbacplus/src/views' => '@app/views/admin/rbac'
                ],
            ],
        ],
        'eauth' => [
            'class' => 'nodge\eauth\EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
            'httpClient' => [
                // uncomment this to use streams in safe_mode
                //'useStreamsFallback' => true,
            ],
            'services' => [
                'steam' => [
                    'class' => 'nodge\eauth\services\SteamOpenIDService',
                    //'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
                    'apiKey' => '466734AF79F76BC407BB76A9FEC8F4F0', // Optional. You can get it here: https://steamcommunity.com/dev/apikey
//                    'apiKey' => '...', // Optional. You can get it here: https://steamcommunity.com/dev/apikey
                ],
                'vkontakte' => [
                    // register your app here: https://vk.com/editapp?act=create&site=1
                    'class' => 'nodge\eauth\services\VKontakteOAuth2Service',
                    'clientId' => '5771737',
                    'clientSecret' => 'KqY7jK8mtJJ1MvU3v83U',
                ],
                'facebook' => [
                    // register your app here: https://developers.facebook.com/apps/
                    'class' => 'nodge\eauth\services\FacebookOAuth2Service',
                    'clientId' => '106341429861402',
                    'clientSecret' => 'f76501ec471c0a9a5075c01c4b9d603e',
                ]
            ],
        ],
        'i18n' => [
            'translations' => [
                'eauth' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@eauth/messages',
                ],
                'kvgrid' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                ],
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'ru',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],
//         'phpBB' => [
//             'class' => 'app\components\nill\forum\phpBB',
//             'path' => $_SERVER['DOCUMENT_ROOT'] . '/web/forum',
// //            'path' => '@app/web/forum',
//         ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'dd.MM.yyyy',
            'datetimeFormat' => 'dd.MM.yyyy HH:mm',
            'locale'=>'ru-RU',
//            'decimalSeparator' => ',',
//            'thousandSeparator' => ' ',
//            'currencyCode' => 'Ru',
        ],
        'urlManager' => [
            'class'=>'app\components\LangUrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                '<action>'=>'site/<action>',
                '<action>'=>'site/<action>',
                'admin/rbac/<controller>'=>'rbac/<controller>',
                'admin/rbac/<controller>/<action>'=>'rbac/<controller>/<action>'
            ],
        ],
        'request' => [
            'class' => 'app\components\LangRequest',
            'csrfParam'=>"xcsrf",
            //'csrfToken'=>"xcsrf",
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'McgsAoDgmm4KLrI9HdExhvTPV8BNIJf2',
            'baseUrl' => '',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
//            'class' => 'nill\forum\PhpBBWebUser',
            'class' => 'yii\web\User',
//            'identityClass' => 'app\models\User',
//            'enableAutoLogin' => true,
            'on afterLogin' => function ($event) {
//                var_dump($event->identity->password_reg);
//                var_dump($event);die();
//                die();
//                $phpbb = new \nill\forum\behaviors\PhpBBUserBahavior();
//                $phpbb->Login($event->identity->name,$event->identity->password_reg);
//                 if ($event->identity->eauth) {
// //                    Yii::$app->phpBB->login($event->identity->username, md5($event->identity->eauth."-".$event->identity->auth_key));
//                     Yii::$app->phpBB->login($event->identity->username, "", true);
//                 } else {
// //                    Yii::$app->phpBB->login($event->identity->username, $event->identity->password_reg);
//                     Yii::$app->phpBB->login($event->identity->username, "", true);
//                 }

//                var_dump(Yii::$app->phpBB->login($event->identity->username,$event->identity->password_reg)); die();
//                \nill\forum\behaviors\PhpBBUserBahavior::className()
            },
            'on afterLogout' => function ($event) {
//                var_dump($event->identity->password_reg);
//                var_dump($event->identity->name);
//                die();
//                $phpbb = new \nill\forum\behaviors\PhpBBUserBahavior();
//                $phpbb->Logout();
                // Yii::$app->phpBB->logout();
//                \nill\forum\behaviors\PhpBBUserBahavior::className()
            }
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            //'defaultRoles' => ['admin', 'author'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false
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
        'db' => require(__DIR__ . '/db.php'),
    ],
    'modules' => [
        'forum2' => [
            'class' => 'app\modules\forum\Forum',
        ],
        'rbac' =>  [
            'class' => 'johnitvn\rbacplus\Module'
        ]
    ],
    'params' => $params,
];

// if (YII_ENV_DEV) {
//     // configuration adjustments for 'dev' environment
//     $config['bootstrap'][] = 'debug';
//     $config['modules']['debug'] = [
//         'class' => 'yii\debug\Module',
//         'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '141.101.1.0','46.33.254.187','46.185.86.57', '46.158.29.186']

//     ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*']
    ];
//    $config['components']['assetManager']['forceCopy'] = true;
// }

return $config;



/*
http://cyber.testsaitov.com/site/login?service=steam
&redirect_uri=http%3A%2F%2Fcyber.testsaitov.com%2Fsite%2Flogin%3Fservice%3Dsteam
&js=&openid.ns=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0
&openid.mode=id_res
&openid.op_endpoint=https%3A%2F%2Fsteamcommunity.com%2Fopenid%2Flogin
&openid.claimed_id=http%3A%2F%2Fsteamcommunity.com%2Fopenid%2Fid%2F76561198006132422
&openid.identity=http%3A%2F%2Fsteamcommunity.com%2Fopenid%2Fid%2F76561198006132422
&openid.return_to=http%3A%2F%2Fcyber.testsaitov.com%2Fsite%2Flogin%3Fservice%3Dsteam%26redirect_uri%3Dhttp%253A%252F%252Fcyber.testsaitov.com%252Fsite%252Flogin%253Fservice%253Dsteam%26js%3D
&openid.response_nonce=2016-03-21T13%3A14%3A28Z9rNGqskbQGY7JpRyfvZRJeASE1Q%3D&openid.assoc_handle=1234567890&openid.signed=signed%2Cop_endpoint%2Cclaimed_id%2Cidentity%2Creturn_to%2Cresponse_nonce%2Cassoc_handle
&openid.sig=pTaff6lxeRerdXA20XA449M7QfE%3D
*/
