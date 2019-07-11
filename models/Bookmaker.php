<?php

namespace app\models;

use Yii;
use yii\db\Query;
/**
 * This is the model class for table "bookmaker".
 *
 * @property integer id_bmaker
 * @property string $name
 * @property string $desc
 * @property string $logo
 * @property string $link
 * @property integer $visible
 *
 * @property News[] $news
 */
class Bookmaker extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    private $sql = '';

    private $bets = [];
    public static function tableName()
    {
        return 'bk';
    }

    const XBET_CODE = 0;
    const EGB_CODE = 1;
    const GG_CODE = 2;
    const PN_CODE = 3;
    const FNB_CODE = 4;
    const VB_CODE = 5;
    const BC_CODE = 6;
    const LB_CODE = 7;
    const MP_CODE = 8;

    public function getNews()
    {
        return $this->hasMany(News::className(), ['id_bmaker' => 'id_bmaker']);
    }

    public static function bestBookmaker()
    {
        return Match::find()
            ->select('league_games.id,

                                            league_games.league_id,
                                            league_games.team1,
                                            league_games.team2,
                                            league_games.date,
                                            leagues.logo as league_logo,
                                            leagues.name as name,
                                            leagues.prize as prize')
            ->leftJoin('leagues', 'leagues.league_id=league_games.league_id');
    }
    private function getApi($query){
        $ch = curl_init($query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_USERAGENT, "24cyber.ru info-bot");
        $data = curl_exec($ch); 
        curl_close($ch);
        return $data;
    }
    private function findGame($data, $id){
        foreach ($data as $value) {
            if(intval($value->id) == intval($id)){
                return $value;
                break;
            }
        }
        return 0;

    }
    private function getQuery($id, $data){
        return 'INSERT INTO egb VALUES("'.$id.'", "'.$data->coef1.'", "'.$data->coef2.'", default); ';
    }
    public function getCoff(){
        $data = json_decode($this->getApi('http://egb.com/api/v1/bets_all?token=e34ccc5145667f91824e1cdc780ff69215b4cd5e1d8b584d'));
        print_r($data);
        // $games = (new Query())->select('id, egb')->from('league_games')->where('egb != 0 AND date >= NOW()')->all();
        // $sql = '';
        // foreach ($games as $value) {
        //     $dataGame = $this->findGame($data, $value['egb']);
        //     if($dataGame != 0){
        //         $sql .= $this->getQuery($value['id'], $dataGame);

        //     }
        // }
        // Yii::$app->db->createCommand($sql)->execute();
        // echo 1;



    }
    private static function checkTeam($team, $name1, $name2){
        $name1 = preg_replace('/\s/', '_', $name1);
        $name2 = preg_replace('/\s/', '_', $name2);
        $team = preg_replace('/\s/', '_', $team);
        if(stristr($team, $name1) !== FALSE || stristr($team, $name2) !== FALSE){
            return true;
        }
        return false;

    }
    private static function checkName($team_names, $id, $name1, $name2){
        $find1 = self::checkTeam($name1, $name2, $name2);

        if(!empty($team_names[$id])){
            foreach ($team_names[$id] as $name) {
                if($find1) break;
                $find1 = self::checkTeam($name, $name2, $name2);
            }
        }
        return $find1;
    }
    private static function checkNames($match, $team_names, $name1, $name2){
        $find1 = self::checkTeam($match['team1_name'], $name1, $name2);
        $find2 = self::checkTeam($match['team2_name'], $name1, $name2);

        if(!empty($team_names[$match['team1_id']])){
            foreach ($team_names[$match['team1_id']] as $name) {
                if($find1) break;
                $find1 = self::checkTeam($name, $name1, $name2);
            }
        }
        if(!empty($team_names[$match['team2_id']])){
            foreach ($team_names[$match['team2_id']] as $name) {
                if($find2) break;
                $find2 = self::checkTeam($name, $name1, $name2);
            }
        }
        if($find1 && $find2){
            return true;
        }
        return false;
    }
    private static function getTeamNames($matches){
        $teams = [];
        foreach ($matches as $match) {
            array_push($teams, $match['team1_id']);
            array_push($teams, $match['team2_id']);
        }
        $teams = array_unique($teams);
        $names = (new Query)->select('*')->from('team_names')->where(['team_id' => $teams])->all();

        $team_names = [];

        foreach ($names as $name) {
            if(empty($team_names[$name['team_id']])){
                $team_names[$name['team_id']] = [];
            }
            array_push($team_names[$name['team_id']], $name['name']);
        }
        return $team_names;
    }
    public function getAllBets($code, $bets){
        $matches = Match::find()->select('m.*, b.bk_id as bk, l.game as game, t1.name as team1_name, t2.name as team2_name, t1.id as team1_id, t2.id as team2_id')->alias('m')
        ->leftJoin('bk b', 'm.id = b.match_id AND b.type = "'.$code.'"')
        ->leftJoin('leagues l', 'l.id = m.league_id')
        ->leftJoin('teams t1', 't1.team_id = m.team1 AND l.game = t1.game')
        ->leftJoin('teams t2', 't2.team_id = m.team2 AND l.game = t1.game')
        ->where(['m.active' => 1, 'm.over' => 0])
        ->andWhere('m.date >= NOW()')
        ->andWhere('m.date < NOW() + INTERVAL 2 DAY')
        ->orderBy('m.date ASC')->asArray()->all();

        //print_r($matches);
        $team_names = self::getTeamNames($matches);
        //print_r($team_names);
        //return;
        foreach ($bets as $bet) {
            foreach ($matches as $key => $match) {
                //print_r($bet);
                if(self::checkNames($match, $team_names, $bet['team1'], $bet['team2'])){
                    if($code == self::PN_CODE || $bet['game'] == $match['game']){
                        $id = $match['id'] * 10 + $code;
                        if(empty($match['bk'])){
                            $this->sql .= LoadData::insert('bk', ['match_id' => $match['id'], 'bk_id' => $id, 'type' => $code]);
                        }

                        if(self::checkName($team_names, $match['team1_id'], $match['team1_name'], $bet['team1'])){
                            $k1 = !empty($bet['k1']) ? $bet['k1'] : 0;
                            $k2 = !empty($bet['k2']) ? $bet['k2'] : 0;
                        }else{
                            $k1 = !empty($bet['k2']) ? $bet['k2'] : 0;
                            $k2 = !empty($bet['k1']) ? $bet['k1'] : 0;
                        }
                        //print_r($match);
                        if(!isset($this->bets[$match['id']])){
                            $this->bets[$match['id']] = [];
                        }
                        array_push($this->bets[$match['id']], ['k1' => $k1, 'k2' => $k2, 'bk' => $code]);
                        $this->sql .= LoadData::insert('bets', ['bk_id' => $id, 'k1' => $k1, 'k2' => $k2]);
                        unset($matches[$key]);
                        break;
                    }
                }
            }
        }

    }
    public function getXbet(){
        $data = DotaHelper::httpGet('https://part.upnp.xyz/PartLine/GetAllFeedGames', ['sportid' => 40]);
        $data = json_decode($data, true);

        $bets = [];

        foreach ($data as $bet) {
            $odd = [];
            if(!empty($bet['A']) && !empty($bet['H'])){
                if(empty($bet['NP'])){
                    if(preg_match('/Dota\s2\./', $bet['C'])){
                        $odd['game'] = DotaHelper::DOTA2_CODE;
                    }else if(preg_match('/CS:GO\./', $bet['C'])){
                        $odd['game'] = DotaHelper::CSGO_CODE;
                    }
                    $odd['team1'] = $bet['H'];
                    $odd['team2'] = $bet['A'];
                    
                    $odd['k1'] = 0;
                    $odd['k2'] = 0;
                    if(!empty($bet['EE'])){
                        if($bet['EE'][1]['T'] == 3){
                            $odd['k2'] = $bet['EE'][1]['C'];
                        }
                        
                        if($bet['EE'][0]['T'] == 1){
                            $odd['k1'] = $bet['EE'][0]['C'];
                        }                        
                    }
                    if(isset($odd['game'])){
                        array_push($bets, $odd);
                    }
            }
            }
        }
        //print_r($bets);
        $this->getAllBets(self::XBET_CODE, $bets);

    }
    public function getGgbet(){
        $data = DotaHelper::httpGet('http://sportsbookfeed.com/sportxml');
        $xml = new \SimpleXMLElement($data);

        $bets = [];
        foreach ($xml->Sport->Event as $event) {
            $bet = [];
            if(preg_match('/Counter-Strike/', $event['Name'])){
                $bet['game'] = DotaHelper::CSGO_CODE;
            }else if(preg_match('/Dota/', $event['Name'])){
                $bet['game'] = DotaHelper::DOTA2_CODE;
            }

            if(isset($bet['game'])){
                foreach ($event->Match as $match_x) {
                    $match = explode(' - ', $match_x['Name']);
                    $bet['team1'] = trim($match[0]);
                    $bet['team2'] = trim($match[1]);

                    $bet['k1'] = 0;
                    $bet['k2'] = 0;
                    
                    if(!empty($match_x->Bet)){
                        $bet['k1'] = $match_x->Bet[0]->Odd[0]['Value'];
                        $bet['k2'] = $match_x->Bet[0]->Odd[1]['Value'];
                    }
                    if($match_x->Bet[0]['Name'] == 'Match Winner'){
                        array_push($bets, $bet);
                    }

                }
            }
        }
        $this->getAllBets(self::GG_CODE, $bets);
    }
    public function getVitalbet(){
        $data = file_get_contents('http://vitalbet.com/sportxml');
        $xml = new \SimpleXMLElement($data);

        $bets = [];
        foreach ($xml->Sport as $sport){
            if($sport['Name'] == 'eSports'){
                foreach ($sport->Event as $event) {
                    //print_r($event);
                    $bet = [];
                    if(preg_match('/Counter-Strike/', $event['Name'])){
                        $bet['game'] = DotaHelper::CSGO_CODE;
                    }else if(preg_match('/Dota/', $event['Name'])){
                        $bet['game'] = DotaHelper::DOTA2_CODE;
                    }

                    if(isset($bet['game'])){
                        foreach ($event->Match as $match_x) {
                            $match = explode(' - ', $match_x['Name']);
                            $bet['team1'] = trim($match[0]);
                            $bet['team2'] = trim($match[1]);

                            $bet['k1'] = 0;
                            $bet['k2'] = 0;
                            
                            if(!empty($match_x->Bet)){
                                $bet['k1'] = $match_x->Bet[0]->Odd[0]['Value'];
                                $bet['k2'] = $match_x->Bet[0]->Odd[1]['Value'];
                            }
                            if($match_x->Bet[0]['Name'] == 'Match Winner'){
                                array_push($bets, $bet);
                            }

                        }
                    }
                }
                break;
            }
        }
        //print_r($bets);
        $this->getAllBets(self::VB_CODE, $bets);
    }
    public function getEgb(){
        $data = DotaHelper::httpGet('https://egb.com/api/v1/bets_all', ['token' => 'e34ccc5145667f91824e1cdc780ff69215b4cd5e1d8b584d']);
        $data = json_decode($data, true);

        $bets = [];
        foreach ($data as $bet) {
            $odd = [];
            if($bet['game'] == 'Counter-Strike'){
                $odd['game'] = DotaHelper::CSGO_CODE;
            }else if($bet['game'] == 'Dota 2'){
                $odd['game'] = DotaHelper::DOTA2_CODE;
            }
            if(isset($odd['game'])){
                $odd['team1'] = $bet['gamer_nick1'];
                $odd['team2'] = $bet['gamer_nick2'];

                $odd['k1'] = $bet['coef1'];
                $odd['k2'] = $bet['coef2'];
                array_push($bets, $odd);
            }
        }
        //print_r($bets);
        $this->getAllBets(self::EGB_CODE, $bets);
    }
    private static function getPData($query){
        //header('Content-type: application/xml');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic '.base64_encode('SA624904:qwerty129!')]);

        $data = curl_exec($ch);

        curl_close ($ch);
        return $data;  
    }
    public function getPinnacle(){
        //$data = json_decode(self::getPData('https://api.pinnacle.com/v1/odds?sportid=12&oddsFormat=DECIMAL'), true);
        $data = json_decode(self::getPData('https://api.pinnacle.com/v1/fixtures?sportId=12'), true);
        //print_r($matches);

        $bets = [];
        foreach ($data['league'] as $league) {
            foreach ($league['events'] as $event) {
                if(!preg_match('/\(.+?\)/', $event['home']) && !preg_match('/\(.+?\)/', $event['away'])){
                    $bet = [];
                    
                    $bet['event_id'] = $event['id'];
                    $bet['team1'] = $event['home'];
                    $bet['team2'] = $event['away'];


                    array_push($bets, $bet);
                    break;
                }                        
            }
        }
        $odds = json_decode(self::getPData('https://api.pinnacle.com/v1/odds?sportid=12&oddsFormat=DECIMAL'), true);
        $b = [];
        foreach ($odds['leagues'] as $league) {
            foreach ($league['events'] as $event) {
                foreach ($bets as $key => $bet) {
                    if($bet['event_id'] == $event['id']){
                        $bet['k1'] = 0;
                        $bet['k1'] = 0;

                        if(!empty($event['periods'][0]['moneyline'])){
                            $bet['k2'] = $event['periods'][0]['moneyline']['away'];
                            $bet['k1'] = $event['periods'][0]['moneyline']['home'];
                        }
                        array_push($b, $bet);
                        unset($bets[$key]);
                        break;
                    }
                }
            }
        }
        $this->getAllBets(self::PN_CODE, $b);
    }
    public function getFanobet(){
        $data = DotaHelper::httpGet('http://fanobet.com/ajax/matchesToBet', ['g' => 7]);
        $data = json_decode($data, true);

        $bets = [];
        foreach ($data['matches'] as $bet) {
            $odd = [];
            if($bet['game'] == 1){
                $odd['game'] = DotaHelper::CSGO_CODE;
            }else if($bet['game'] == 4){
                $odd['game'] = DotaHelper::DOTA2_CODE;
            }
            if(isset($odd['game'])){
                $odd['team1'] = $bet['team1']['name'];
                $odd['team2'] = $bet['team2']['name'];

                $odd['k1'] = $bet['odds']['s1'];
                $odd['k2'] = $bet['odds']['s2'];
                array_push($bets, $odd);
            }
        }
        $this->getAllBets(self::FNB_CODE, $bets);
    }
    public function getBetClub(){
        $data = DotaHelper::httpGet('http://7.b7bet.com/api/api/line/ru-ru/tournaments/sport300?addParams[0]={addParams[0]}&addParams[1]={addParams[1]}api/line/ru-ru/sports/full');
        $data = json_decode($data, true);

        $bets = [];
        foreach ($data as $bet) {
            foreach ($bet['EventsHeaders'] as $event) {
                $odd = [];
                if(preg_match('/Counter-Strike/', $event['CountryName'])){
                    $odd['game'] = DotaHelper::CSGO_CODE;
                }else if(preg_match('/Dota/', $event['CountryName'])){
                    $odd['game'] = DotaHelper::DOTA2_CODE;
                }
                //print_r($bet['EventsHeaders']);
                if(isset($odd['game'])){
                    $odd['team1'] = $event['TeamsGroup'][0];
                    $odd['team2'] = $event['TeamsGroup'][1];

                    $odd['k1'] = floatval(str_replace(',', '.', $event['Markets'][0]['Rates'][0]['Rate']));
                    $odd['k2'] = floatval(str_replace(',', '.', $event['Markets'][0]['Rates'][2]['Rate']));
                    array_push($bets, $odd);
                }
            }
        }
        //print_r($bets);
        $this->getAllBets(self::BC_CODE, $bets);
    }
    public function getLootBet(){
        $data = file_get_contents('http://loot.bet/sportxml');
        $xml = new \SimpleXMLElement($data);

        $bets = [];
        //print_r($xml->Sport[0]);
        foreach ($xml->Sport as $sport) {
            if($sport['Name'] == 'eSports'){
                foreach ($sport->Event as $event) {
                    $bet = [];
                    if(preg_match('/Counter-Strike/', $event['Name'])){
                        $bet['game'] = DotaHelper::CSGO_CODE;
                    }else if(preg_match('/Dota/', $event['Name'])){
                        $bet['game'] = DotaHelper::DOTA2_CODE;
                    }
                    if(isset($bet['game'])){
                        foreach ($event->Match as $match_x) {
                            $match = explode(' - ', $match_x['Name']);
                            $bet['team1'] = trim($match[0]);
                            $bet['team2'] = trim($match[1]);

                            $bet['k1'] = 0;
                            $bet['k2'] = 0;
                    
                            if(!empty($match_x->Bet)){
                                $bet['k1'] = $match_x->Bet[0]->Odd[0]['Value'];
                                $bet['k2'] = $match_x->Bet[0]->Odd[1]['Value'];
                            }
                            if($match_x->Bet[0]['Name'] == 'Match Winner'){
                                array_push($bets, $bet);
                            }
                        }
                    }
                }
                break;
            }
        }
        $this->getAllBets(self::LB_CODE, $bets);
    }
    public function getMaraphone(){
        $data = file_get_contents('http://livefeeds.marathonbet.com/feed/24cyber_pre');
        $xml = new \SimpleXMLElement($data);

        $bets = [];
        //print_r($xml->sport[0]->groups->group);

        foreach ($xml->sport[0]->groups->group as $event) {
            //print_r($event);
            $bet = [];
            if(preg_match('/Дота 2/', $event['name'])){
                $bet['game'] = DotaHelper::DOTA2_CODE;
            }else if(preg_match('/Контр-Страйк/', $event['name'])){
                $bet['game'] = DotaHelper::CSGO_CODE;
            }
            
            if(isset($bet['game'])){
                //print_r($event);
                //return;
                foreach ($event->events->event as $match_x) {
                    $bet['team1'] = (string)$match_x->teams->team1['name'];
                    $bet['team2'] = (string)$match_x->teams->team2['name'];
                    
                    $bet['k1'] = 0;
                    $bet['k2'] = 0;
            
                    if(!empty($match_x->markets)){
                        foreach ($match_x->markets->market as $odd){
                            if($odd['model'] == 'MTCH_R'){
                                foreach ($odd->sel as $market) {
                                    if($market['selkey'] == 'H'){
                                        $bet['k1'] = $market['coeff'];
                                    }else if($market['selkey'] == 'A'){
                                        $bet['k2'] = $market['coeff'];
                                    }
                                }
                                break;
                            }
                            
                        }
                    }
                    if(!empty($bet['team1']) && !empty($bet['team2'])){
                        array_push($bets, $bet);
                    }
                    
                }
            }
        }
        //print_r($bets);
        $this->getAllBets(self::MP_CODE, $bets);
    }

    public function getForks(){
        //print_r($this->bets);
        $forks = [];
        foreach ($this->bets as $match_id => $match) {
            $k1_max = $match[0];
            $k2_max = $match[0];

            foreach ($match as $bet) {
                if($bet['k1'] >= $k1_max['k1']){
                    $k1_max = $bet;
                }
                if($bet['k2'] >= $k2_max['k2']){
                    $k2_max = $bet;
                }

            }
            if($k2_max['k2'] > 0 && $k1_max['k1'] > 0){
                $s = (1 / $k1_max['k1']) + (1 / $k2_max['k2']);
                if($s < 1){
                    $fork = Fork::find()->where(['match_id' => $match_id])->with('match')->one();

                    $profit = (100 / $s) - 100;
                    $new = false;

                    if(!$fork){
                        $fork = new Fork();
                        $new = true;
                    }else if($profit > $fork['profit']){
                        $new = true;
                    }
                    //$new = true;
                    $f = [];
                    $f['date'] = date('Y-m-d H:i:s');

                    $f['k1'] = $k1_max['k1'];
                    $f['bk1'] = $k1_max['bk'];
                    
                    $f['k2'] = $k2_max['k2'];
                    $f['bk2'] = $k2_max['bk'];

                    $f['profit'] = $profit;

                    $f['match_id'] = $match_id;

                    print_r($f);
                    $fork->attributes = $f;

                    $fork->save();
                    //print_r($fork->bk1_link);
                    if($new){
                        foreach ($fork->email as $mail) {
                            Yii::$app->mailer->compose('fork', ['profit' => $profit, 'fork' => $fork])
                                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.' (отправлено роботом)'])
                                ->setTo($mail['email'])
                                ->setSubject('Вилка с прибылью - '.round($profit, 2).'%')
                                ->send();
                        }
                    }
                    
                }
            }
        }
    }
    public function s(){
        Yii::$app->db->createCommand($this->sql)->execute();
    }
    public static function getLogo($type){
        switch ($type) {
            case self::EGB_CODE:
                return 'egb.png';
                break;
            case self::GG_CODE:
                return 'ggbet.png';
                break;
            case self::PN_CODE:
                return 'pinnacle.png';
                break;
            case self::XBET_CODE:
                return '1xbet.png';
                break;
        }
    }
    public static function getName($type){
        switch ($type) {
            case self::EGB_CODE:
                return 'EGB';
                break;
            case self::GG_CODE:
                return 'GGbet';
                break;
            case self::PN_CODE:
                return 'Pinnacle';
                break;
            case self::XBET_CODE:
                return '1Xbet';
                break;
            case self::FNB_CODE:
                return 'Fanobet';
                break;
            case self::VB_CODE:
                return 'Vitalbet';
                break;
            case self::BC_CODE:
                return 'BetClub';
                break;
            case self::LB_CODE:
                return 'LootBet';
                break;
            case self::MP_CODE:
                return 'Marathon';
                break;
        }
    }
    public static function getBets($bets){
        $b = [];
        foreach ($bets as $bet) {
            $data = (new Query)->select('*')->from('bets')->where(['bk_id' => $bet['bk_id']])->orderBy('time ASC')->all();
            $b[self::getName($bet['type'])]['k1'] = [];
            $b[self::getName($bet['type'])]['k2'] = [];
            foreach ($data as $bs) {
                array_push($b[self::getName($bet['type'])]['k1'], [strtotime($bs['time']), $bs['k1']]);
                array_push($b[self::getName($bet['type'])]['k2'], [strtotime($bs['time']), $bs['k2']]);
            }
        }
        return $b;
    }
    // public function getDesc(){
    //     return (new Query)->select('*')->from('bk_desc')->where(['type' => $this->type])->one();
    // }

}
