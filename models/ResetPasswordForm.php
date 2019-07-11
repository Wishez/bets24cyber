<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 26.02.2016
 * Time: 16:37
 */

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;

class ResetPasswordForm extends Model
{
    public $password;
    private $_user;
    public function rules()
    {
        return [
            ['password', 'required']
        ];
    }
    public function attributeLabels()
    {
        return [
            'password' => 'Пароль'
        ];
    }
    public function __construct($key, $config = [])
    {
        if(empty($key) || !is_string($key))
            throw new InvalidParamException('Ключ не может быть пустым.');
        $this->_user = User::findByPasswordResetToken($key);
        if(!$this->_user)
            throw new InvalidParamException('Не верный ключ.');
        parent::__construct($config);
    }
    public function resetPassword()
    {
        /* @var $user User */
        $user = $this->_user;
        Yii::$app->phpBB->changePassword($user->username,$this->password);
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        return $user->save();
    }


}