<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 26.02.2016
 * Time: 16:35
 *
* @var $user \app\models\User
*/
use yii\helpers\Html;
echo 'Привет '.Html::encode($user->username).'. ';
echo Html::a('Для активации аккаунта перейдите по этой ссылке.',
    Yii::$app->urlManager->createAbsoluteUrl(
        [
            '/site/reset-password',
            'key' => $user->password_reset_token
        ]
    ));