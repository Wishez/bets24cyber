<?php

namespace app\models;

use Yii;
use yii\helpers\StringHelper;
use app\models\League;
use app\models\Team;
use yii\db\Query;

/**
 * This is the model class for table "match".
 *
 * @property integer $id_match
 * @property integer $id_match_steam
 * @property integer $radiant_team_id
 * @property integer $dire_team_id
 * @property integer $start_time
 * @property string $link_video
 * @property string $chats
 * @property integer $id_league
 * @property integer $match_sort_number
 * @property integer id_steam_team1
 * @property integer id_steam_team2
 */
class Match extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $radiant_team_name;
    public $name;
    public $league_id_league;
    public $id_brate;
    public $league_logo;
    public $league_prize;
    public $parsing_date;
    public $cat;
    public $subcat;
    public $match_date;
    public $favorites;
    public $prize;
    public $sub_date_correction;
    public $match_period;
    public $id_steam_team1;
    public $id_steam_team2;
    public $game_score;

    public $team1_name;
    public $team1_flag;
    public $team1_logo;
    public $team1_id;

    public $team2_name;
    public $team2_flag;
    public $team2_logo;
    public $team2_id;

    public $teams1 = null;
    public $teams2 = null;

    public $team1_sid;
    public $team2_sid;

    public $game;
    public $bk;

    public $favorite;

    public $dota_games = null;
    public $csgo_games = null;
    public function rules()
    {
        return [
            [['team1', 'team2','score_left', 'score_right', 'date'], 'safe']
        ];
    }
    public static function tableName()
    {
        return 'matches';
    }
    public function getLeague()
    {
        return $this->hasOne(League::className(), ['id' => 'league_id']);
    }
    public function getBets()
    {
        return $this->hasMany(Bookmaker::className(), ['match_id' => 'id']);
    }

    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['match_id' => 'id']);
    }

    public function getTeamOneRatio()
    {
        $score = 0;
        $order = $this->hasMany(Orders::className(), ['match_id' => 'id'])->where(['winner'=>2,'status'=>1])->orderBy(['id'=> SORT_DESC])->limit(1)->one();
        if ($order) {
            $score = ($order->summ*$order->rate)/(($order->summ*$order->rate)-$order->summ);
        }
        return round($score, 2);
    }


    public function getTeamOneRatioLast()
    {
        $score = 0;
        $order_last = $this->hasMany(Orders::className(), ['match_id' => 'id'])->where(['winner'=>2,'status'=>1])->orderBy(['id'=> SORT_DESC])->limit(1)->one();
        if ($order_last) {
            $order = $this->hasMany(Orders::className(), ['match_id' => 'id'])->where(['winner'=>2,'status'=>1])->andWhere(['!=','id', $order_last->id])->orderBy(['id'=> SORT_DESC])->limit(1)->one();
            if ($order) {
                $score = ($order->summ*$order->rate)/(($order->summ*$order->rate)-$order->summ);
            }
        }
        
        return $score ? round($score, 2) : -1;
    }

    public function getTeamTwoRatio()
    {
        $score = 0;
        $order = $this->hasMany(Orders::className(), ['match_id' => 'id'])->where(['winner'=>1,'status'=>1])->orderBy(['id'=> SORT_DESC])->one();
        if ($order) {
            $score = ($order->summ*$order->rate)/(($order->summ*$order->rate)-$order->summ);
        }
        return round($score, 2);
    }


    public function getTeamTwoRatioLast()
    {
        $score = 0;
        $order_last = $this->hasMany(Orders::className(), ['match_id' => 'id'])->where(['winner'=>1,'status'=>1])->orderBy(['id'=> SORT_DESC])->limit(1)->one();
        if ($order_last) {
            $order = $this->hasMany(Orders::className(), ['match_id' => 'id'])->where(['winner'=>1,'status'=>1])->andWhere(['!=','id', $order_last->id])->orderBy(['id'=> SORT_DESC])->limit(1)->one();
            if ($order) {
                $score = ($order->summ*$order->rate)/(($order->summ*$order->rate)-$order->summ);
            }
        }
        return $score ? round($score, 2) : -1;
    }
    // public function getTeams1(){
    //     //return $this->hasOne(Team::className(), ['team_id' => 'team1'])->andWhere(['game' => $this->league->game]);

    //     return $this->hasOne(Team::className(), ['team_id' => 'team1']);
    // }
    // public function getTeams2(){
    //     return $this->hasOne(Team::className(), ['team_id' => 'team2']);
    // }
    public function getTeamOne()
    {
        $team = $this->hasOne(Team::className(), ['team_id' => 'team1'])->where(['game' => $this->league->game]);
        if ($team->count() == 0) {
            $team = new Team();
            $team->team_id = $this->team1;
            $team->name = $this->team1;
            $team->earning = 0;
            $team->logo = '/img/logos/empty_logo.png';
            $team->flag = '/img/flags/eflag.png';
            $team->game = $this->game;
        }

        return $team;
    }
    public function getTeamTwo()
    {
        $team = $this->hasOne(Team::className(), ['team_id' => 'team2'])->where(['game' => $this->league->game]);
        if ($team->count() == 0) {
            $team = new Team();
            $team->team_id = $this->team2;
            $team->name = $this->team2;
            $team->earning = 0;
            $team->logo = '/img/logos/empty_logo.png';
            $team->flag = '/img/flags/eflag.png';
            $team->game = $this->game;
        }

        return $team;
    }
    public function getTeamFirst()
    {
        $team = $this->teams1;
        if (empty($team)) {
            $team = new Team();
            $team->id = !empty($this->team1_id) ? $this->team1_id : 0;
            $team->team_id = $this->team1;
            $team->name = !empty($this->team1_name) ? $this->team1_name : $this->team1;
            $team->earning = 0;
            $team->logo = !empty($this->team1_logo) ? $this->team1_logo : '/img/logos/empty_logo.png';
            $team->flag = !empty($this->team1_flag) ? '/img/flags/'.$this->team1_flag.'.png' : '/img/flags/eflag.png';
            $team->game = $this->game;
            $team->spec_id = $this->team1_sid;
            $this->teams1 = $team;
        }

        return $team;
    }
    public function getTeamSecond()
    {
        $team = $this->teams2;
        if (empty($team)) {
            $team = new Team();
            $team->id = !empty($this->team2_id) ? $this->team2_id : 0;
            $team->team_id = $this->team2;
            $team->name = !empty($this->team2_name) ? $this->team2_name : $this->team2;
            $team->earning = 0;
            $team->logo = !empty($this->team2_logo) ? $this->team2_logo : '/img/logos/empty_logo.png';
            $team->flag = !empty($this->team2_flag) ? '/img/flags/'.$this->team2_flag.'.png' : '/img/flags/eflag.png';
            $team->game = $this->game;
            $team->spec_id = $this->team2_sid;
            $this->teams2 = $team;
        }

        return $team;
    }
    public function getStreams()
    {
        return self::find()->select('*')->from('twitch')->where(['match_id' => $this->id])->groupBy('channel')->orderBy('sort')->asArray()->all();
    }
    public function getTwitch()
    {
        return $this->hasMany(Streams::className(), ['match_id' => 'id'])->groupBy('channel')->orderBy('sort');
    }
    public function getVods()
    {
        return self::find()->select('video')->from('vods')->where(['match_id' => $this->id])->groupBy('video')->asArray()->all();
    }

    public function getCsgoGames()
    {
        return $this->hasMany(CsgoGames::className(), ['match_id' => 'hltv_id'])->alias('m')
        ->viaTable('hltv_csgo', ['match_id' => 'id'])->leftJoin('hltv_csgo c', 'c.hltv_id = m.match_id')->select('c.series as series, m.*');
    }
    public function getDotaGames()
    {
        return $this->hasMany(DotaMatch::className(), ['m_id' => 'id']);
    }
    public function getScore()
    {
        if ($this->league->game == DotaHelper::CSGO_CODE) {
            $data = $this->csgoGames;
            if (!empty($data)) {
                $score_left = 0;
                $score_right = 0;

                foreach ($data as $game) {
                    if ($game['team1_id'] == $this->teamOne->spec_id && $game['score_left'] > $game['score_right']) {
                        $score_left++;
                    }
                    if ($game['team1_id'] == $this->teamTwo->spec_id && $game['score_left'] > $game['score_right']) {
                        $score_right++;
                    }
                    if ($game['team2_id'] == $this->teamTwo->spec_id && $game['score_left'] < $game['score_right']) {
                        $score_right++;
                    }
                    if ($game['team2_id'] == $this->teamOne->spec_id && $game['score_left'] < $game['score_right']) {
                        $score_left++;
                    }
                }
                $score_left = $score_left >= $this->score_left ? $score_left : $this->score_left;
                $score_right = $score_right >= $this->score_right ? $score_right : $this->score_right;
                return $score_left.'-'.$score_right;
            }
        } elseif ($this->league->game == DotaHelper::DOTA2_CODE) {
            $data = $this->dotaGames;
            if (!empty($data)) {
                $score_left = 0;
                $score_right = 0;

                foreach ($data as $game) {
                    if ($game->active == 0) {
                        if ($game['radiant_id'] == $this->teamOne->spec_id || $game['dire_id'] == $this->teamTwo->spec_id) {
                            $left = true;
                            $right = false;
                        } else {
                            $left = false;
                            $right = true;
                        }

                        // print_r($game);
                        // var_dump($left);
                        // var_dump($right);
                        // var_dump($game->radiant_win);
                        if ($left && $game['radiant_win'] == 1) {
                            $score_left++;
                        }
                        if ($left && $game['radiant_win'] == 0) {
                            $score_right++;
                        }
                        if ($right && $game['radiant_win'] == 1) {
                            $score_right++;
                        }
                        if ($right && $game['radiant_win'] == 0) {
                            $score_left++;
                        }
                    }
                }
                if ($this->score_left + $this->score_right > $score_left + $score_right) {
                    return $this->score_left.'-'.$this->score_right;
                } else {
                    return $score_left.'-'.$score_right;
                }
            }
        }
        return $this->score_left.'-'.$this->score_right;
    }
    public function getGameOver()
    {
        if ($this->over == 1) {
            return 1;
        }
        $score = explode('-', $this->score);

        if ($score[0] == 0 && $score[1] == 0) {
            return 0;
        }

        $series = $this->series;
        if ($this->league->game == DotaHelper::CSGO_CODE) {
            if (!empty($this->csgoGames)) {
                $series = $this->csgoGames[0]['series'];
            }
        }

        //echo $series;
        $max_score = ($series + 1) / 2;


        $over = 0;
        if ($score[0] == $max_score || $score[1] == $max_score) {
            return 1;
        } elseif (($series % 2 == 0) && ($score[0] + $score[1] == $series)) {
            return 1;
        }

        return $this->over;
    }
    public static function allMatches()
    {
        return Match::find()
            ->select('ISNULL(f.id_user) as favorite, m.id as id, m.date as date,
                m.score_left as score_left,  m.score_right as score_right, m.over as over,
                t1.name as team1_name, t1.logo as team1_logo, t1_f.flag as team1_flag, t1.id as team1_id,
                t2.id as team2_id,
                t2.name as team2_name, t2.logo as team2_logo, t2_f.flag as team2_flag,
                t1.spec_id as team1_sid, t2.spec_id as team2_sid,
                l.game as game, m.*')
            ->alias('m')
            ->LeftJoin('favorites f', 'f.id_match = m.id AND f.id_user = '.(empty(Yii::$app->user->id) ? 0 : Yii::$app->user->id))
            ->leftJoin('leagues l', 'l.id = m.league_id')
            ->leftJoin('teams t1', 't1.team_id = m.team1 AND t1.game = l.game')
            ->leftJoin('flags t1_f', 't1_f.country = t1.flag')
            ->leftJoin('teams t2', 't2.team_id = m.team2 AND t2.game = l.game')
            ->leftJoin('flags t2_f', 't2_f.country = t2.flag')
            ->andWhere(['m.active' => 1, 'l.close'=>0]);
    }
    public static function all($game, $over)
    {
        return self::allMatches()->where(['m.active' => 1, 'm.over' => $over, 'l.game' => $game])->with('bets');
    }
    public static function getLastMatches()
    {
        return self::allMatches()->with('dotaGames')->with('csgoGames')->with('bets')->andWhere(['m.over' => 1])->orderBy('m.date DESC');
    }
    public static function getActiveMatches()
    {
        return self::allMatches()->with('bets')->with('dotaGames')->with('csgoGames')->andWhere(['m.over' => 0])->orderBy('m.date ASC');
    }
    public function getGameList($index)
    {
        /*
        return Match::findBySql('SELECT

                                  match.id_match,
                                  match.link_video,
                                  match.id_league,
                                  match.match_sort_number,
                                  match.type_of_game,
                                  match.team1,
                                  match.team2,
                                  league.logo as league_logo


                                 FROM `match`
                                 LEFT JOIN league on league.id_league=match.id_league
                                 WHERE match.type_of_game="'.$index.'" AND match.match_sort_number="1" ')->all();*/
        return League::findBySql('SELECT * FROM league WHERE type_of_game="' . $index . '" AND visible="1" ')->all();
    }

    public static function getMatch($where, $order)
    {
        $matches = Match::find()
                            ->select('g.team1 as team1_id,
                                        g.id as id,
                                        g.team2 as team2_id,
                                        g.date as date,
                                        g.score as score,
                                        l.game as game,
                                        l.name as league,
                                        t1.name as team1_name,
                                        t1.flag as team1_flag,
                                        t2.name as team2_name,
                                        t2.flag as team2_flag,')
                            ->from('league_games g')
                            ->leftJoin('leagues l', 'l.league_id LIKE g.league_id')
                            ->leftJoin('teams t1', 't1.team_id LIKE g.team1 AND t1.game = l.game')
                            ->leftJoin('teams t2', 't2.team_id LIKE g.team2 AND t2.game = l.game')
                            ->where($where)
                            ->orderBy('g.date '.$order)
                            ->limit(200)
                            ->asArray()->all();
        return $matches;
    }


    public static function getCorrectDateTime($str)
    {
        $ddat = explode(' ', $str);
        $ddmas_d = explode('-', $ddat[0]);
        $ddmas_time = explode(':', $ddat[1]);
        return mktime($ddmas_time[0], $ddmas_time[1], 0, $ddmas_d[1], $ddmas_d[0], $ddmas_d[2]);
    }
    public static function addMatch($match, $sc)
    {
        $team1 = !empty($match['team1_name']) ? $match['team1_name'] : $match['team1_id'];
        $team2 = !empty($match['team2_name']) ? $match['team2_name'] : $match['team2_id'];
        echo ' <a class="match_block" href="/match-details?id='.$match['id'].'">

        <div class="match-info1">
        <div class="team-block">

        <div class="team-flag team-flag1" style="background-image: url(\'/img/flags/'.Match::country($match['team1_flag']).'.png\');"></div>
            <span class="team-name first">'.$team1.'</span>
        </div>
        <span class="versus-match">'.$sc.'</span>
        <div class="team-block">
            <div class="team-flag team-flag2" style="background-image: url(\'/img/flags/'.Match::country($match['team2_flag']).'.png\');"></div>
            <span class="team-name second">'.$team2.'</span>
        </div></div>
        <span class="time"><div class="time-half">'.$match['league'].'</div><div class="time-half">'.$match['date'].'</div></span>
        </a>';
    }
    public static function indexSelector($matches_a, $matches_l, $leagues_a, $leagues_l, $game)
    {
        $active = ['match' => 0, 'league' => 0];
        $last = ['match' => 0, 'league' => 0];
        $max = 30;
        echo '<div class="stream">
        <div class="header-index">
          <div class="upper-chose">
            <div class="button-league-match match_ch">Матчи</div>
            <div class="button-league-match league_ch">Лиги</div>
          </div>
          <div class="line_ch"></div>
        </div>

        <div class="last-active">
          <div class="button-chs active-button">Активные</div>
          <div class="button-chs last-button">Прошедшие</div>
        </div>
            <div class="matches" data-lm="'.$game.'">
            <div class="side-block active match-actives match">';

        foreach ($matches_a as $value) {
            if ($active['match'] <= $max) {
                if ($value['game'] == $game) {
                    if (strtotime($value['date']) < time()) {
                        self::addMatch($value, $value['score']);
                    } else {
                        self::addMatch($value, 'vs');
                    }
                    $active['match']++;
                }
            } else {
                break;
            }
        }
        echo' </div>
            <div class="side-block nota match-last match">';
        foreach ($matches_l as $value) {
            if ($last['match'] <= $max) {
                if ($value['game'] == $game) {
                    if (strtotime($value['date']) < time()) {
                        self::addMatch($value, $value['score']);
                        $last['match']++;
                    }
                }
            } else {
                break;
            }
        }
        echo '</div>
            <div class="side-block nota league-actives league">';
        foreach ($leagues_a as $value) {
            if ($active['league'] <= $max) {
                if ($value['game'] == $game) {
                    League::addLeague($value);
                    $active['league']++;
                }
            } else {
                break;
            }
        }
        echo '
           </div>
           <div class="side-block nota league-last league">';
        foreach ($leagues_l as $value) {
            if ($last['league'] <= $max) {
                if ($value['game'] == $game) {
                    League::addLeague($value);
                    $last['league']++;
                }
            } else {
                break;
            }
        }
        echo '</div>
        </div>
        ';
    }
    public static function addRowMatch($data)
    {
        $left = $data->left->win == 0 ? 'lose' : 'win';
        $right = $data->right->win == 0 ? 'lose' : 'win';
        echo '                <div class="stat-row">
                    <div class="team team-left">
                        <div class="heroes">
                            <img src="http://wiki.teamliquid.net/commons/images/thumb/3/39/Invoker.png/43px-Invoker.png">
                            <img src="http://wiki.teamliquid.net/commons/images/thumb/3/39/Invoker.png/43px-Invoker.png">
                            <img src="http://wiki.teamliquid.net/commons/images/thumb/3/39/Invoker.png/43px-Invoker.png">
                            <img src="http://wiki.teamliquid.net/commons/images/thumb/3/39/Invoker.png/43px-Invoker.png">
                            <img src="http://wiki.teamliquid.net/commons/images/thumb/3/39/Invoker.png/43px-Invoker.png">
                        </div>
                        <div class="status-block '.$left.'"></div>
                    </div>
                    <div class="team team-right">
                    <div class="status-block '.$right.'"></div>
                        <div class="heroes">
                            <img src="http://wiki.teamliquid.net/commons/images/thumb/3/39/Invoker.png/43px-Invoker.png">
                            <img src="http://wiki.teamliquid.net/commons/images/thumb/3/39/Invoker.png/43px-Invoker.png">
                            <img src="http://wiki.teamliquid.net/commons/images/thumb/3/39/Invoker.png/43px-Invoker.png">
                            <img src="http://wiki.teamliquid.net/commons/images/thumb/3/39/Invoker.png/43px-Invoker.png">
                            <img src="http://wiki.teamliquid.net/commons/images/thumb/3/39/Invoker.png/43px-Invoker.png">
                        </div>
                    </div>
                </div>';
    }

    public static function getPlayersName($steamids)
    {
        $query = '';
        for ($i = 0; $i < count($steamids); $i++) {
            $steamid = bcadd($steamids[$i], '76561197960265728');

            if ($i != count($steamids) - 1) {
                $query .= $steamid.',';
            } else {
                $query .= $steamid;
            }
        }
        $a = json_decode(file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=466734AF79F76BC407BB76A9FEC8F4F0&steamids='.$query));
        $names = [];
        foreach ($a->response->players as $value) {
            $steamid = bcsub($value->steamid, '76561197960265728');
            $names[$steamid] = $value->personaname;
        }
        return $names;
    }
    public static function addTeamTable($data, $names, $g)
    {
        $name = $g == 5 ? $data->result->dire_name : $data->result->radiant_name;
        $score = $g == 5 ? $data->result->dire_score : $data->result->radiant_score;
        $class = $g == 5 ? 'dire' : 'radiant';
        echo '<div class="team-game-details">
                        <table class="game-details-table">
                            <thead>
                                <tr>
                                    <th style="min-width: 100px; text-align: left;"><div class="team '.$class.'">'.$name.'</div><div class="score">Score: '.$score.'</div></th>
                                    <th>K</th>
                                    <th>D</th>
                                    <th>A</th>
                                    <th class="gold">Gold</th>
                                    <th>LH/DN</th>
                                    <th class="gold">GPM</th>
                                    <th>XPM</th>
                                    <th>Damage</th>
                                    <th>Heal</th>
                                    <th style="max-width: 90px; line-height: 1.1em;">Tower</br>Damage</th>
                                </tr>
                            </thead>
                            <tbody>';
        for ($i = $g; $i < $g + 5; $i++) {
            $player = $data->result->players[$i];
            echo '<tr>
                                    <td style="text-align: left;"><div class="player-game-details">
                                        <div class="img-part" style="background-image: url(\''.self::GetHeroImg($player->hero_id).'\')"></div>
                                        <div class="part-hero">
                                            <div class="player">
                                                <span>'.$names[$player->account_id].'</span>
                                            </div>
                                        </div>
                                    </div></td>
                                    <td>'.$player->kills.'</td>
                                    <td>'.$player->deaths.'</td>
                                    <td>'.$player->assists.'</td>
                                    <td class="gold">'.$player->gold.'</td>
                                    <td>'.$player->last_hits.'/'.$player->denies.'</td>
                                    <td class="gold">'.$player->gold_per_min.'</td>
                                    <td>'.$player->xp_per_min.'</td>
                                    <td>'.$player->hero_damage.'</td>
                                    <td>'.$player->hero_healing.'</td>
                                    <td>'.$player->tower_damage.'</td>
                                </tr>';
        }
        echo '
                            </tbody>
                        </table>
                    </div>';
    }
    public static function addTable($id, $i)
    {
        $data = json_decode(file_get_contents('https://api.steampowered.com/IDOTA2Match_570/GetMatchDetails/v1/?key=466734AF79F76BC407BB76A9FEC8F4F0&format=json&match_id='.$id));

        $steamids = [];
        foreach ($data->result->players as $value) {
            array_push($steamids, $value->account_id);
        }
        $names = self::getPlayersName($steamids);
        $class = $i != 0 ? 'hide-bracket' : 'active-bracket';
        echo '<div class="game-details '.$class.'" id="bracket-'.$i.'">';
        Match::addTeamTable($data, $names, 0);
        Match::addTeamTable($data, $names, 5);
        echo '</div>';
    }
    public static function getImg($all, $name)
    {
        $name = preg_replace('/_/', ' ', strtolower($name));
        $name = $name == 'dark-seer' ? 'dark seer' : $name;
        for ($i = 0; $i < count($all); $i++) {
            if (strtolower($all[$i]['name']) == $name) {
                return 'http://cdn.dota2.com/apps/dota2/images/heroes/'.$all[$i]['hero_name'].'_sb.png';
            }
        }
        return $name;
    }
    public static function getImgId($all, $id)
    {
        for ($i = 0; $i < count($all); $i++) {
            if ($all[$i]['hero_id'] == $id) {
                return 'http://cdn.dota2.com/apps/dota2/images/heroes/'.$all[$i]['hero_name'].'_sb.png';
            }
        }
    }
}
