<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 26.02.2016
 * Time: 16:29
 */

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class SendEmailForm extends Model
{
    public $email;
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::className(),
                'filter' => [
                    'status' => User::STATUS_ACTIVE
                ],
                'message' => 'Данный емайл не зарегистрирован.'
            ],
        ];
    }
    public function attributeLabels()
    {
        return [
            'email' => 'Емайл'
        ];
    }
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne(
            [
                'status' => User::STATUS_ACTIVE,
                'email' => $this->email
            ]
        );

        if($user):

            $user->generatePasswordResetToken();

            if($user->save()):
                return Yii::$app->mailer->compose('resetPassword', ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.' (отправлено роботом)'])
                    ->setTo($this->email)
                    ->setSubject('Сброс пароля для '.Yii::$app->name)
                    ->send();
            endif;

//            var_dump($user->errors);die();
        endif;
        return false;
    }
    public function sendReg($user){


        if($user){
                return Yii::$app->mailer->compose('activeUser', ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.' (отправлено роботом)'])
                    ->setTo($user->email)
                    ->setSubject('Сброс пароля для '.Yii::$app->name)
                    ->send();
      }

        return false;
    }


}