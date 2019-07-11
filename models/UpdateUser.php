<?php
namespace app\models;

use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 22.03.2016
 * Time: 15:17
 */
class UpdateUser extends Model
{
    public $email;
    public $password;

    public function rules()
    {
        return [

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Этот адрес электронной почты уже занято.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'password' => 'Пароль'
        ];
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function edit()
    {
        if ($this->validate()) {
            $user = Yii::$app->user->identity;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                Yii::$app->phpBB->userAdd($user->username, $this->password, $user->email, 2);
//                Yii::$app->phpBB->login($event->identity->username, $event->identity->password_reg);
                return $user;
            }
        }

        return null;
    }
}