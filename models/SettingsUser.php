<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 25.02.2016
 * Time: 10:15
 */

namespace app\models;


use Yii;
use yii\base\Event;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class SettingsUser extends Model
{

    public $birthday;
    public $avatar = "";

    private $_user = false;

    public function __construct() {
        $this->_user = User::findOne(Yii::$app->user->id);
        $this->birthday = $this->_user->birthday;
        $this->avatar = $this->_user->avatar;

        parent::__construct();
    }

    /**
     * @return array
     */
    public function behaviors()
    {

        /*Event::on(ActiveRecord::className(), ActiveRecord::EVENT_BEFORE_INSERT, function ($event) {
            if ($this->avatar != "") {
                $this->saveAvatar();
            }
        });*/
        return [
            TimestampBehavior::className(),

        ];
    }


    public function rules()
    {
        return [
            [['birthday'], 'date', 'format' => 'dd-mm-yyyy'],
//            [['birthday'], 'string'],
            [['avatar'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif', 'maxSize' => 1024 * 1024 * 5],

        ];
    }

    public function attributeLabels()
    {
        return [
            'avatar' => 'Добавить / Заменить аватар',
            'birthday' => 'День рождения',
        ];
    }
    public function save() {

        if ($this->validate()) {
            $this->_user->birthday = $this->birthday;
            $this->_user->avatar = $this->avatar;

            if ($this->avatar != "") {
                $this->saveAvatar();
            }
            return $this->_user->save();
        }
        return false;
    }



    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    private function saveAvatar()
    {
        Yii::$app->phpBB->changeAvatar($this->_user->username,$this->_user->avatar);

    }
}