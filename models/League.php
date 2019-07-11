<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\db\Query;
use yii\widgets\ListView;
/**
 * This is the model class for table "league".
 *
 * @property integer $id_league
 * @property integer $id_league_steam
 * @property string $name
 * @property integer $prize
 * @property string $link
 * @property string $stream
 * @property string $chats
 */
class League extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'leagues';
    }
    public function rules()
    {
        return [
            [['league_id', 'name', 'image', 'date_start', 'date_end', 'prizepool', 'about', 'game', 'event'], 'safe']
        ];
    }
    /**
     * @inheritdoc
     */
    public $qual_id;
    public $bracket;

    public $category_name;
    public $league_search_text;
    public $league_search_type;

    public $select1;
    public $select2;
public $game1;
public $name1;

public $games;
public $match_date;

public $id2;
public $frame;
public $chat;
public $type;

public $data;

    public function getQuals()
    {
        return $this->hasMany(self::className(), ['id' => 'qual_id'])
            ->viaTable('league_quals', ['league_id' => 'league_id']);
    }

    public function getMain()
    {
        return $this->hasOne(self::className(), ['league_id' => 'league_id'])
            ->viaTable('league_quals', ['qual_id' => 'id']);
    }
    public function getMatches(){
        return Match::find()
            ->select('m.*, ISNULL(f.id_user) as favorite')
            ->alias('m')
            ->LeftJoin('favorites f', 'f.id_match = m.id AND f.id_user = '.(empty(Yii::$app->user->id) ? 0 : Yii::$app->user->id)) 
            ->where(['league_id' => $this->id, 'active' => 1]);
    }
    public function getMatches1(){
        return Match::find()
            ->select('m.*, ISNULL(f.id_user) as favorite')
            ->alias('m')
            ->LeftJoin('favorites f', 'f.id_match = m.id AND f.id_user = '.(empty(Yii::$app->user->id) ? 0 : Yii::$app->user->id)) 
            ->where(['active' => 1]);
    }
    public function getActiveMatches(){
            $league_id = [];
            foreach ($this->quals as $qual) {
                foreach ($qual->quals as $q) {
                    array_push($league_id, $q->id);
                }
                array_push($league_id, $qual->id);
            }
            array_push($league_id, $this->id);
            return Match::allMatches()->andWhere(['m.over' => 0])->andWhere(['m.league_id' => $league_id])->orderBy('m.date ASC'); 
        
    }
    public function getLastMatches(){
            $league_id = [];
            foreach ($this->quals as $qual) {
                foreach ($qual->quals as $q) {
                    array_push($league_id, $q->id);
                }
                array_push($league_id, $qual->id);
            }
            array_push($league_id, $this->id);
            return Match::allMatches()->andWhere(['m.over' => 1])->andWhere(['m.league_id' => $league_id])->orderBy('m.date DESC'); 

        
    }
    public function getImageS(){
        if($this->event == 1){
            $this->image = !empty($this->main) ? $this->main->image : null;
        }
        if(empty($this->image)){
            $this->image = '/img/logos/empty_logo.png';
        }
        return $this->image;
    }
    public function getKvals()
    {
        if(!empty($this->main)){
            $league_id = $this->main->league_id;
        }else{
            $league_id = $this->league_id;
        }   
        return $this::find()->select('*')->from('league_quals')->where(['league_id' => $league_id])->all();
        
    }
    public function getQs()
    {
        return $this::find()->select('*')->from('league_quals')->where(['league_id' => $this->league_id])->all();
        
    }
    public function getTeams(){
        $teams = Team::find()->select('t.id, t.name, p.place, prize')->alias('t')->leftJoin('league_place p', 'p.team LIKE t.team_id')->where(['p.league_id' => $this->id])->all();
        usort($teams, function($a, $b){
            $a = explode('-', $a->place)[0];
            $b = explode('-', $b->place)[0];
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        });
        return $teams;
    } 
    public function getBrackets(){
        $brackets = self::find()->select('*')->from('league_bracket')->where(['league_id' => $this->id])->all();
        return $brackets;
    }
    public function getTable(){
        return self::find()->select('*')->from('league_table')->where(['league_id' => $this->id])->asArray()->one();
    }
    public function getGroups(){
        return self::find()->select('*')->from('league_groups')->where(['league_id' => $this->id])->asArray()->all();
    }
    public static function remove($id){
        $sql = '';
        $sql .= Yii::$app->db->createCommand()->delete('leagues', ['id' => $id])->getRawSql().'; ';
        $sql .= Yii::$app->db->createCommand()->delete('matches', ['league_id' => $id])->getRawSql().'; ';
        $sql .= Yii::$app->db->createCommand()->delete('league_quals', ['qual_id' => $id])->getRawSql().'; ';
        $sql .= Yii::$app->db->createCommand()->delete('league_bracket', ['league_id' => $id])->getRawSql().'; ';
        $sql .= Yii::$app->db->createCommand()->delete('league_place', ['league_id' => $id])->getRawSql().'; ';

        return $sql;
    }
    public function getHrefLink($str)
    {
        $str1=strpos($str,'<a href="');
        $str2=strpos($str,'" title="');
        //$get_link_team_math=substr($str,$str1+9,$str2-$str1-9);
        return substr($str,$str1+9,$str2-$str1-9);
    }

    public function goGoCurl($str)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $str);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Chrome 11');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $answer = curl_exec($ch);
        $errmsg = curl_error($ch);
        curl_close($ch);

        return $answer;
    }

    public function giveTeamName($str)
    {
        /*
         $clear_str_match=strip_tags($str); //убирает все HTML теги.
        $str1=strpos($clear_str_match,'(');
        $str2=strripos($clear_str_match,'%)');

        if (($str1!=false)AND($str2!=false))
        {
            $dlina=strlen($clear_str_match);
            $str_team1=trim(substr($clear_str_match,0,$str1));
            $str_pro_team=trim(substr($clear_str_match,$str2+2,$dlina-$str2));

            $str3=strpos($str_pro_team,' ');
            $str_team2=trim(substr($str_pro_team,0,$str3));

            $dlina_pro=strlen($str_pro_team);
            $str_data=trim(substr($str_pro_team,$str3,$dlina_pro-$str3));
        }
        else
        {
            $str1=strpos($clear_str_match,'vs');
            $str_team1=trim(substr($clear_str_match,0,$str1));

            $bufer=trim(substr($clear_str_match,$str1+2,strlen($clear_str_match)-$str1+2));

            $str2=strpos($bufer,' ');
            $str_team2=trim(substr($bufer,0,$str2));
            $str_data=trim(substr($bufer,$str2,strlen($bufer)-$str2));
        }
        */
        $work_str=$str;
        //print $work_str.'<p>';

        $str1=strpos($work_str,'teamname c1');

        $work_str=trim(substr($work_str,$str1+17,strlen($work_str)-$str1));
        //print $work_str.'<p>';

        $str2=strpos($work_str,'</b>');
        $team1=trim(substr($work_str,3,$str2-3));

        $work_str=trim(substr($work_str,$str2,strlen($work_str)-$str2));

        $str1=strpos($work_str,'teamname c2');
        $work_str=trim(substr($work_str,$str1+17,strlen($work_str)-$str1));

        $str2=strpos($work_str,'</b>');
        $team2=trim(substr($work_str,3,$str2-3));
        $work_str=trim(substr($work_str,$str2,strlen($work_str)-$str2));

        $str1=strpos($work_str,'live-in');
        $work_str=trim(substr($work_str,$str1+9,strlen($work_str)-$str1));

        $str2=strpos($work_str,'<');
        $time=trim(substr($work_str,0,$str2));

        return $answer=[
            'team1'=>$team1,
            'team2'=>$team2,
            'data' =>$time,];


    }

    public function giveMeLegueData($str)
    {
        $pos1=strripos($str,'с ');
        if ($pos1==0)
            $pos1=strripos($str,'в ');

        $str=trim(substr($str,$pos1+2,strlen($str)-$pos1+2));
        /*
        2016-02-19
        10 февраля, 2016
        9 февраля, 2016
        18 января, 2016
        2016-02-09
        29 февраля, 2016
        июне 2016
        29 февраля, 2016
        */
        return $str;
    }


    public function getMatchLink($id, $document)
    {
        $mas_team_name_dota = explode('|', ($document->find("tbody tr h4 a")->prepend('|')));

        $work_str=$mas_team_name_dota[$id];
        $str1=strpos($work_str,'href="');
        $work_str=substr($work_str,$str1+6,strlen($work_str)-$str1+6);
        $str1=strpos($work_str,'"');
        //$work_str=substr($work_str,0,$str1);
        return substr($work_str,0,$str1);
    }

    public function getLogoTeam($str)
    {
        $sstr=trim($str);
        $pos1=strpos($sstr,'src="');
        $str_logo=substr($sstr,$pos1+5,strlen($sstr)-$pos1);

        $pos1=strpos($str_logo,'">');

        return substr($str_logo,0,$pos1);
    }

    public function getTrueDataTime($stroka)
    {
        $mas_month=['января'=>'1', 'февраля'=>'2', 'марта'=>'3', 'апреля'=>'4', 'мая'=>'5', 'июня'=>'6', 'июля'=>'7', 'августа'=>'8', 'сентября'=>'9', 'октября'=>'10', 'ноября'=>'11', 'декабря'=>'12',];

        $str=str_replace('  ',' ',trim(strip_tags($stroka)));
        $mas=explode(',',$str);
        $mas_time_day_month=explode(' ',$mas[0]);
        $mas_time=explode(' ',$mas[1]);
        $mas_time_detail=explode(':',$mas_time[1]);
        $result=mktime($mas_time_detail[0], $mas_time_detail[1], 0, $mas_month[trim($mas_time_day_month[1])], trim($mas_time_day_month[0]), $mas_time_day_month[2]);
        return $result;
    }


    public function getTrueDataTime_dota($stroka)
    {
        $mas_month=['января'=>'1', 'февраля'=>'2', 'марта'=>'3', 'апреля'=>'4', 'мая'=>'5', 'июня'=>'6', 'июля'=>'7', 'августа'=>'8', 'сентября'=>'9', 'октября'=>'10', 'ноября'=>'11', 'декабря'=>'12',];

        $str=str_replace('  ',' ',trim(strip_tags($stroka)));
        $mas=explode(',',$str);
        $mas_time_day_month=explode(' ',$mas[0]);
        $mas_time=explode(' ',$mas[2]);
        $mas_time_detail=explode(':',$mas_time[1]);
        $result=mktime($mas_time_detail[0], $mas_time_detail[1], 0, $mas_month[trim($mas_time_day_month[1])], trim($mas_time_day_month[0]), trim($mas[1]));
        return $result;
    }

    public function getLeagueDate($stroka)
    {

        $mas_month=['января'=>'1', 'февраля'=>'2', 'марта'=>'3', 'апреля'=>'4', 'мая'=>'5', 'июня'=>'6', 'июля'=>'7', 'августа'=>'8', 'сентября'=>'9', 'октября'=>'10', 'ноября'=>'11', 'декабря'=>'12',];

        $str=str_replace(', ',' ',trim(strip_tags($stroka)));

        if (strpos($str,'-'))
        {
            $mas=explode('-',$str);
            $result=mktime(0, 0, 0,  $mas[1], $mas[2] ,$mas[0]);
        }
        else
        {
            $mas=explode(' ',$str);
            $result=mktime(0, 0, 0,  $mas_month[$mas[1]], $mas[0] ,$mas[2]);
        }
        return $result;
    }
//     <div class="league-logo" style="background-image: url(\''.$league['logo'].'\');"></div>
// <div class="league-name">'.$league['name'].'</div>
// <div class="league-date">'.$league['date_start'].'</div>
    public function addLeague($league){
        preg_match('/^(.+?)\//', $league['league_id'], $n);
        $name = empty($league['name']) ? $n[1] : $league['name'];
        echo '<a class="match_block" href="/league-details?id='.$league['id'].'">
            <div class="league-logo" style="background-image: url(\''.$league['image'].'\');" title="'.$name.'"></div>
            <div class="league-name">'.$name.'</div>
            <div class="league-date">'.$league['date_start'].'</div>

  </a>';
    }
    public static function getDate($data)
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

        $str=date('d',$data).' '.$mas_month[date('n',$data)].' '.date('Y',$data);
        return $str;
    }
    public static function activeLeague($game){
           echo'<h4><span>Призовой фонд </span>текущих турниров</h4>
        <div class="hr"></div>

        <div class="prognoses">';

if(isset($game) && ($game == '0' || $game == '1')){
    $tourners = League::find()->where('date_start >= NOW() AND game = '.$game)->limit(8)->orderBy('prizepool DESC')->asArray()->all();
}else $tourners = League::find()->where('date_start >= NOW()')->limit(10)->orderBy('prizepool DESC')->asArray()->all();
            
            foreach ($tourners as $league) { 
                $prize = !empty($league['prizepool']) ? $league['prizepool'] : 0;
                echo '<div class="prognos-item">
                    <h4>
                        <a href="'.Url::to(['site/league-details', 'id' => $league['id']]).'">'.$league['name'].'</a>
                    </h4>
                    <p>
                        <i class="fa fa-clock-o"></i><span>'.$league['date_start'].'</span>
                    </p>
                    <p class="usd"><i class="fa fa-usd"></i><span>'.$prize.'</span></p>
                </div>';
            } 
        echo '</div>';
    }
    public static function matchInfo($data, $pages){


    }
    public static function leagueGames($data, $pages){
                    print GridView::widget([
                'dataProvider' => new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => [
                    'pageSize' => 28,
                    'pageParam' => $pages[0],
                    'pageSizeParam' => $pages[1]
                ],
            ]),
                'tableOptions' => ['class' => 'stream-table'],
                'showHeader' => false,
                'layout' => "{items}\n{summary}\n{pager}",
                'pager' => [
                    'options' => ['class' => 'pagin']
                ],
                'summaryOptions' => [
                    'tag' => 'p'
                ],
                'columns' =>
                    [

                        'team1' => [
                            'attribute' => 'team1',
                            'format' => 'html',
                            'value' => function ($model2) {
                                 $team = empty($model2['team1_name']) ? $model2['team1_id'] : $model2['team1_name'];
                                return Html::img('/img/flags/'.Match::country($model2['team1_flag']).'.png', ['class' => 'team_logo'])
                                .Html::a($team, Url::to(['site/match-details','id' => $model2['id']]), ['class' => 'team_name']);
                            },
                        ],
                        [
                            'format' => 'html',
                            'value' => function ($model2) {
                                return "<span class='vs'>".$model2['score']."</span>";
                            },
                        ],
                        'team2' => [
                            'attribute' => 'team2',
                            'format' => 'html',
                            'value' => function ($model2) {
                                $team = empty($model2['team2_name']) ? $model2['team2_id'] : $model2['team2_name'];
                                return Html::img('/img/flags/'.Match::country($model2['team2_flag']).'.png', ['class' => 'team_logo'])
                                .Html::a($team, Url::to(['site/match-details','id' => $model2['id']]), ['class' => 'team_name']);
                            },
                        ],
                        'parsing_date' => [
                            'attribute' => 'parsing_date',
                            'format' => 'html',
                            'value' => function ($model) {
                                if($model['date'] == "0000-00-00 00:00:00"){
                                    return Html::tag('span', "Неизвестно", ['class' => 'date-time']);
                                }
                                return Html::tag('span', $model['date'], ['class' => 'date-time']);
                            },
                        ],
                        'league_prize' => [
                            'attribute' => 'league_prize',
                            'format' => 'raw',
                            /*'value' => function ($model) {
                                return Html::a(Html::tag('span', '<i class="fa fa-usd"></i>', ['class' => 'usd', 'title' => $model['prize']]), '#');
                            },*/
                            'value' => function ($model) {
                                $class = $model['favorites'] == 0 ? "fa-star" : "fa-star-o";
                                $title = $model['favorites'] == 0 ? "В избранном" : "Добавить в избранное";
                                if (Yii::$app->user->isGuest) {
                                    return false;
                                }
                                return Html::a(Html::tag('span', '<i class="fa  ' . $class . '"></i>', ['class' => 'usd']),
                                    '#',
                                    [
                                        'class' => "add_favorites",
                                        'title' => $title,
                                        'data' => [
                                            'matchid' => $model['id']
                                        ]
                                    ]
                                );
                            },
                        ],
                    ]
            ]);
    }
}



