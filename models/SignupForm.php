<?php
namespace app\models;

use app\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Это имя пользователя уже занято.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

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
            'username' => 'Имя',
            'email' => 'E-mail',
            'password' => 'Пароль'
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $data = [];
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;

            $user->setPassword($this->password);
            $user->generateAuthKey();

            $user->status = User::STATUS_ACTIVE;

            $user->created_at = time();
            $user->updated_at = time();

            if ($user->save(false)) {
                return $user;
            }
        }

        return false;
    }
    public function activateUser($id, $auth){
        $user = User::findOne(
            [
                'status' => User::STATUS_DELETED,
                'id' => $id
            ]
        );
        if($user){
            if($user->validateAuthKey($auth)){
                $user->status = User::STATUS_ACTIVE;
                $user->save();
                Yii::$app->getUser()->login($user);
                return true;
            }
        }
        return false;
    }
}
