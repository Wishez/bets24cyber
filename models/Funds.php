<?php
namespace app\models;


//use phpbb\log\null;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property mixed userraiting
 * @property mixed avatar
 * @property mixed birthday
 * @property string role
 */
class Funds extends ActiveRecord
{


//    public $eauth;

//    public $birthday;
//    public $avatar;

//    public $userraiting;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{fund}}';
    }
    public function rules()
    {
        return [[['name', 'updated_at', 'balance', 'created_at'], 'safe']];
    }


}
