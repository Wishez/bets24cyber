<?php
namespace app\models;

use app\components\nill\forum\behaviors\PhpBBUserBahavior;
//use phpbb\log\null;
use Yii;
use yii\base\ErrorException;
use yii\base\Event;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;

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
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $password_reg;
    public $password_new;
    public $post_text;
    public $post_time;

    /**
     * @var array EAuth attributes
     */
    public $profile;
    public $authKey;

//    public $eauth;

//    public $birthday;
//    public $avatar;

//    public $userraiting;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{bets_users}}';
    }




    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['username', 'password_hash'], 'string', 'max' => 255],
            ['username', 'required'],
            ['email', 'email'],
            [['email', 'username', 'password_reset_token'], 'unique'],
            [['role', 'eauth'], 'string'],
            ['role', 'default', 'value' => 'user'],
            [['birthday', 'eauth'], 'safe'],
            [['avatar'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif', 'maxSize' => 1024 * 1024 * 5],

        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя',
            'email' => 'E-mail',
            'password_hash' => 'Пароль'
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
//            var_dump("<pre>",$this);die();
            /*if ($this->avatar != "") {
                $this->saveAvatar();
            }*/
            return true;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        if (Yii::$app->getSession()->has('user-' . $id)) {
            return new self(Yii::$app->getSession()->get('user-' . $id));
        } else {
            return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
//            return isset(self::$users[$id]) ? new self(self::$users[$id]) : null;
        }
    }

    /**
     * @param \nodge\eauth\ServiceBase $service
     * @return User
     * @throws ErrorException
     */
    public static function findByEAuth($service)
    {
        if (!$service->getIsAuthenticated()) {
            throw new ErrorException('EAuth user should be authenticated before creating identity.');
        }
        $eauth = $service->getServiceName() . '-' . $service->getId();
//        var_dump($service);die();
        if (!$user = self::findOne(['eauth' => $eauth])) {
            $user = new User();

            if ($service->getServiceName() == 'steam') {
                $user->username = $service->getAttribute('personaname') . "(steam)";
                $user->avatar = $service->getAttribute('avatar');
            } elseif ($service->getServiceName() == 'vkontakte') {
                $user->username = $service->getAttribute('name') . "(vk)";
                $user->avatar = $service->getAttribute('photo');
            } elseif ($service->getServiceName() == 'facebook') {
                $user->username = $service->getAttribute('name') . "(fb)";
                $user->avatar = '//graph.facebook.com/'.$service->getAttribute('id').'/picture';
            }

            $user->eauth = $eauth;
            $user->generateAuthKey();

            if (!$user->save()) {
                return false;
            }
        }

        $user->profile = $service->getAttributes();
        $user->profile['service'] = $service->getServiceName();
        Yii::$app->getSession()->set('user-' . $eauth, $user);
        return new self($user);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }


    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($key)
    {
        if (!static::isPasswordResetTokenValid($key)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $key,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($key)
    {
        if (empty($key)) {
            return false;
        }
        $expire = Yii::$app->params['secretKeyExpire'];
        $parts = explode('_', $key);
        $timestamp = (int)end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        $this->password_reg = $password;
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function password($password)
    {
        $this->password_new = $password;

        $this->setPassword($password);
        return $this->save(false);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_reg = $password;
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new password reset token
     */
    /* public function generatePasswordResetToken1()
     {
         $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
     }*/

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['author_id' => 'id']);
    }

    public function getOrdersTransactions()
    {
        return $this->hasMany(Transaction::className(), ['agent' => 'id'])->where([ 'type'=>Transaction::TYPE_ORDER]);
    }

    public function getMyTransactionsIn()
    {
        return $this->hasMany(Transaction::className(), ['agent' => 'id'])->where([ 'type'=>[Transaction::TYPE_REFIll]]);
    }

    public function getMyTransactionsOut()
    {
        return $this->hasMany(Transaction::className(), ['partner' => 'id'])->where([ 'type'=>[Transaction::TYPE_RETURN]]);
    }

    public function getPays()
    {
        return $this->getOrdersTransactions()->andWhere(['not in', 'partner', ArrayHelper::map($this->orders, 'id', 'id')]);
    }


    /**
     * @param bool $id
     * @return $this->userraitingremovePasswordResetToken
     */
    public function getUserraiting()
    {
        $id = $this->id;
        if (!$id) {
            $id = \Yii::$app->user->id;
        }
        $username = $this->findOne($id)->username;
        $commentsRaiting = CommentsRating::find()
            ->leftJoin('comments', 'comments.id_comment = comments_rating.id_comment')
            ->where(['comments.user_id' => $id, 'comments.active' => 1])
            ->sum('comments_rating.rait');

        $forumRaiting = $this->find()->select('user_reputation')->from("forum_users")->where(['username' => $username])->asArray()->all()[0]['user_reputation'];
        return $forumRaiting + $commentsRaiting;
    }

    /**
     * @param bool $id
     * @return $this->forumpost
     */
    public function getUserPost($id = false)
    {
        if (!$id) {
            $id = \Yii::$app->user->id;
        }
        $username = $this->findOne($id)->username;
        $forumPost = $this->find()
            ->select(['post_text', 'post_time'])
            ->from('forum_posts')
            ->leftJoin('forum_users', 'forum_users.user_id = forum_posts.poster_id')
            ->where(['forum_users.username' => $username, 'post_visibility' => 1])
            ->orderBy(['forum_posts.post_time' => SORT_DESC]);
        $userComments = $this->find()
            ->select(['text', 'created_at'])
            ->from('comments')
            ->where(['active' => '1', 'user_id' => $id])
            ->orderBy(['comments.created_at' => SORT_DESC]);
        $forumPost->union($userComments);
        $sql = $forumPost->createCommand()->getRawSql();
        $query = $this->findBySql($sql)->asArray()->all();
        return $query;
    }


    /**
     * @param $match_id
     * @return string
     */
    public static function setFavorites($match_id)
    {
        $fav = Favorites::findOne(['id_match' => $match_id, 'id_user' => Yii::$app->user->id]);
        if ($fav) {
            return "isset";
        }
        $fav = new Favorites();
        Yii::$app->db->createCommand("INSERT INTO favorites VALUES(".Yii::$app->user->id.", ".$match_id.")")->execute();
        return "ok";
    }

    public static function delFavorites($match_id)
    {
        $fav = Favorites::findOne(['id_match' => $match_id, 'id_user' => Yii::$app->user->id]);
        if ($fav->delete()) {
            return "ok";
        }
        return "err";
    }

    public static function getFavorites()
    {
        $subQuery = (new Query())->select('id_match')->from('favorites')->where(['id_user' => Yii::$app->user->id]);
        $fav = Match::find()
                         ->select('g.team1 as team1_id,
                                    g.id as id,
                                    g.team2 as team2_id,
                                    g.date as date,
                                    g.score as score,
                                    t1.name as team1_name,
                                    t1.flag as team1_flag,
                                    t2.name as team2_name,
                                    t2.flag as team2_flag,
                                    l.name as league,
                                    l.logo as logo')
                         ->from('league_games g')
                         ->leftJoin('leagues l', 'l.league_id LIKE g.league_id')
                         ->leftJoin('teams t1', 't1.team_id LIKE g.team1 AND t1.game = l.game')
                         ->leftJoin('teams t2', 't2.team_id LIKE g.team2 AND t2.game = l.game')
                         ->andWhere(['g.id'=> $subQuery])
                         ->orderBy('date DESC')
                         ->asArray()->all();
//        $fav = Match::getModel_match()->andWhere(['id_match'=>$subQuery])->asArray()->all();
        return $fav;
    }
}
