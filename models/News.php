<?php

namespace app\models;

use Yii;
use yii\base\Event;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "news".
 *
 * @property integer $id_news
 * @property string $title
 * @property string $text
 * @property string $img
 * @property integer $id_user
 * @property integer $id_category
 * @property integer id_bmaker
 * @property integer $show_in_footer
 * @property integer $createt_at
 * @property integer $updated_at
 * @property integer $sort
 *
 * @property Bookmaker $idBookmaker
 * @property Category $idCategory
 * @property User $idUser
 */
class News extends ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $category_name;
    public $user_username;

    public $where_search;
    public $what_search;


    public static function tableName()
    {
        return 'news';
    }
    public function behaviors()
    {

        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_BEFORE_INSERT, function ($event) {
            $this->id_user = Yii::$app->user->id;
        });
        return [
            TimestampBehavior::className(),

        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','desc' ,'text'], 'string'],
            [['title','text'], 'required'],
            [['id_user', 'id_category', 'id_bmaker', 'show_in_footer', 'created_at', 'updated_at', 'sort'], 'integer'],
            [['img'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_news' => 'Id',
            'title' => 'Название новости',
            'desc'=>'Краткое описание',
            'text' => 'Текст',
            'img' => 'Новостная картика',
            'id_user' => 'User',
            'id_category' => 'Тематическая категория новости',
            'id_bmaker' => 'Букмекерская фирма',
            'show_in_footer' => 'Где отображать',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'sort' => 'Сортировка',
            'categoryname'=>'Категория',
            'category_name'=>'Категория',
            'user_username'=>'Пользователь',
            'what_search'=>'Что искать',
            'where_search'=>'Где искать',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return (new Query)->select('t.*')->from('news_tag n')->leftJoin('tags t', 't.id = n.tag')->where(['n.news_id' => $this->id_news])->all();
    }
    public function getShared(){
        return Match::find()->select('*')->from('news_share')->where(['news_id' => $this->id_news])->asArray()->one();
    }
    public function getSimilarNews(){
        $tags = [];
        foreach ($this->tags as $tag) {
            array_push($tags, $tag['tag']);
        }
        return (new Query)->select('t.news_id as id, n.img as img, n.title as title, n.created_at as date')
            ->from('news_tag t')
            ->leftJoin('news n', 'n.id_news = t.news_id')
            ->leftJoin('tags s', 't.tag = s.id')
            ->where(['s.tag' => $tags])
            ->andWhere('t.news_id != "'.$this->id_news.'" AND  n.id_news is not null')
            ->groupBy('t.news_id')
            ->having('COUNT(t.news_id) = 1')
            ->limit(10)->all();
    }
    public function getBookmaker()
    {
        return $this->hasOne(Bookmaker::className(), ['id_bmaker' => 'id_bmaker']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id_category' => 'id_category']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
    public function getCategory_name()
    {
        return $this->getCategory()->one()['name'];
    }


    public function saveTags($tags){
        $tags = (new Query)->select('id')->from('tags')->where(['tag' => $tags])->all();
        $sql = 'DELETE FROM news_tag WHERE news_id = '.$this->id_news.' ;';
        foreach ($tags as $tag) {
            $sql .= 'INSERT INTO news_tag VALUES("", "'.$this->id_news.'", "'.$tag['id'].'"); ';
        }
        Yii::$app->db->createCommand($sql)->execute();
    }
    public static function getNewsDataTime($data)
    {
        $mas_month=[
            1=>'Января',
            2=>'Февраля',
            3=>'Марта',
            4=>'Апреля',
            5=>'Мая',
            6=>'Июня',
            7=>'Июля',
            8=>'Августа',
            9=>'Сентября',
            10=>'Октября',
            11=>'Ноября',
            12=>'Декабря',
        ];

        $str=date('d',$data).' '.$mas_month[date('n',$data)].' '.date('Y',$data).', '.date('H:i',$data);
        return $str;
    }
    public static function sliderNews($news, $game){
        echo '<div class="news">
            <div class="news_slider">
            <div class="button_slide glyphicon glyphicon-menu-left slide_left"></div>
            <div class="slider-over">
            <div class="slider-block">';
    foreach ($news as $value) {
        $value['img'] = "'".$value['img']."'";
            if($value['id_category'] == $game && $value['show_in_footer'] == 1){
                echo '<a class="news-block" href="'.Url::to(['@web/site/news', 'id_news' => $value['id_news']]).'"
                 style="background-image: url('.str_replace('http:', 'https://', $value['img']).')">
            <div class="date-news">'.News::getNewsDataTime($value['updated_at']).'</div>
            <div class="text-news">'.StringHelper::truncate($value['title'], 40, '...').'</div>
            <div class="filter"></div>
          </a>';
            }
        } 
      
    echo '</div>
    </div>
    <div class="button_slide glyphicon glyphicon-menu-right slide_right"></div>
    </div>
    </div>';
    }
    public static function mainNews($news, $game){

    }
    public static function rollNews($news, $game){
        echo '<div class="roll-news">';
        foreach ($news as $value) {
            if($value['id_category'] == $game && $value['show_in_footer'] == 0){
                $value['img'] = "'".$value['img']."'";
                echo '<a href="'.Url::to(['@web/news', 'id_news' => $value['id_news']]).'">
                <div class="news-block-main">
                <div class="img" style="background-image: url('.str_replace('http:', 'https://', $value['img']).');" ></div>
                <span>'.StringHelper::truncate($value['title'], 40, '...').'</span>
                <div class="filter"></div>
                </div>
                </a>';
            }
        }
        echo '</div>';
    }



}
