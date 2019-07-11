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
class UserFields extends ActiveRecord
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
        return '{{user_fields}}';
    }
    public static function dataTypes(){
        return ['Текст', 'Число', 'Дата', 'Дата и время'];
    }
    public static function getDBtype($code){
        switch ($code) {
            case 1:
                return 'INT';
                break;
            default:
                return 'VARCHAR(255)';
                break;
        }
    }
    public function getFormType(){
        switch ($this->data_type) {
            case 1:
                return 'num';
                break;
            default:
                return 'text';
                break;
        }

    }
    public function getColumn(){
        return 'col_id_'.$this->id;
    }
    public static function translit($str) {
        $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 
            'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');

        $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 
            'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 
            'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
        return preg_replace('/\s/', '_', str_replace($rus, $lat, $str));
  }
    public function rules()
    {
        return [[['name', 'data_type', 'require_signup', 'require_pay'], 'safe']];
    }
    public function addColumn(){
        //$name = self::translit($this->name);
        $name = 'col_id_'.$this->id;
        $type = self::getDBtype($this->data_type);
        $sql = 'ALTER TABLE bets_users ADD '.$name.' '.$type.' NULL ;';
        try{
            Yii::$app->db->createCommand($sql)->execute();
        }catch(\Exception $e){

        }
    }
    public function removeColumn(){
        $name = 'col_id_'.$this->id;
        //$name = self::translit($this->name);
        $sql = 'ALTER TABLE bets_users DROP '.$name.';';
        try{
            Yii::$app->db->createCommand($sql)->execute();
        }catch(\Exception $e){
            //echo $e->getMessage();
            //throw '1';
        }
    }


}
