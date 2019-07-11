<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\League;
use yii\db\Query;
use app\models\GetTeams;
use app\models\TeamData;
use app\models\GetLeagueData;
use app\models\ParseBracket;


class Addleague{
   private function saveLeague($data, $event){
    if(!empty($data->logo)){
      if(preg_match('/http:\/\/wiki\.teamliquid\.net\//', $data->logo)){
      $name = preg_replace('/http:\/\/wiki\.teamliquid\.net\//', "", $data->logo);
      $name = preg_replace('/\//', "_", $name);
      $d = file_get_contents($data->logo);
      $fp = fopen("/var/www/admin/www/24cyber.ru/web/img/logos/".$name, "w");
      fwrite($fp, $d);
      fclose($fp);
      $data->logo = '/img/logos/'.$name;
    }
    }

      return 'INSERT INTO leagues 
      VALUES("", "'.$data->league_id.'", "'.$data->name.'", "'.$data->logo.'", "'.$data->date_start.'", "'.$data->date_end.'", "'.$data->prizepool.'", 
      "'.$data->about.'", \''.$data->stream.'\', \''.$data->chat.'\', '.$event.', '.$data->game.') 
      ON DUPLICATE KEY 
      UPDATE name = "'.$data->name.'", logo = "'.$data->logo.'", date_start = "'.$data->date_start.'", date_end = "'.$data->date_end.'"
      , prize = "'.$data->prizepool.'", about = "'.$data->about.'", stream = \''.$data->stream.'\', chat = \''.$data->chat.'\'; 
      DELETE FROM league_place WHERE league_id = "'.$data->league_id.'"; DELETE FROM league_players WHERE league_id = "'.$data->league_id.'"; 
      DELETE FROM league_games WHERE league_id = "'.$data->league_id.'"; SET @new_index = IFNULL((SELECT MAX(id) FROM league_games ), 0); SET @sql = CONCAT("ALTER TABLE league_games AUTO_INCREMENT = ", @new_index); PREPARE st FROM @sql; EXECUTE st; ';
        
   }
   private function saveMatch($data, $id){
      $date = date('Y-m-d', strtotime($data->date));
      $index = $data->team1.'-'.$data->team2.'-'.$date.'-'.$id;
      return 'INSERT INTO league_games 
      VALUES("", "'.$id.'", "'.$index.'", "'.$data->team1.'", "'.$data->team2.'", "'.$data->date.'", "'.$data->score.'", \''.$data->stream.'\', \''.$data->chat.'\', "'.$data->egb.'") 
      ON DUPLICATE KEY 
      UPDATE score = "'.$data->score.'", stream = \''.$data->stream.'\', chat = \''.$data->chat.'\', egb = "'.$data->egb.'"; ';
   }
   private function getGame($link){
      if(preg_match('/http:\/\/wiki.teamliquid.net\/(.+?)\/(.+)$/', $link)){
            preg_match('/http:\/\/wiki.teamliquid.net\/(.+?)\/(.+)$/', $link, $matches);
            $ret = [];
            if($matches[1] == 'dota2'){
                $ret['game'] = 0;
            }else{
                $ret['game'] = 1;
            }
            $ret['league_id'] = $matches[2];
            return $ret;
      }else{
            return 0;
      }
   }
      private function saveGame($obj, $index){
        $obj = preg_replace('/\'/', "", json_encode($obj));
            return 'INSERT INTO league_games_b 
      VALUES("'.$index.'", \''.$obj.'\') 
      ON DUPLICATE KEY 
      UPDATE data = \''.$obj.'\'; ';
   }
   private function savePlayer($player, $team, $league){
      return 'INSERT INTO league_players 
      VALUES("'.$team.'", "'.$league.'", "'.$player.'"); ';
   }



   private function saveBracket($link, $id){
      $t = new ParseBracket();
      $t->setLeague($id);
      $data = $t->start(file_get_contents($link));
      if(count($data['bracket']) == 1){
        $data['bracket'][0]['name'] = null;
      }
      $sql2 = 'DELETE FROM league_bracket WHERE league_id LIKE "'.$id.'"; ';
      for ($i = 0; $i < count($data['bracket']); $i++) { 
        $sql2 .= 'INSERT INTO league_bracket VALUES("'.$id.'", "'.$data['bracket'][$i]['name'].'", "'.$data['bracket'][$i]['bracket'].'"); ';
      }
      for ($i = 0; $i < count($data['details']); $i++) { 
        $sql2 .= $this->saveGame($data['details'][$i]['game'], $data['details'][$i]['ix']);
      }
      return $sql2;
      //print_r($data);
    }

         private function saveLink($link, $id, $name){
              return 'INSERT INTO league_links 
      VALUES("'.$link.'", "'.$name.'", "'.$id.'") 
      ON DUPLICATE KEY 
      UPDATE name = "'.$name.'"; ';
         }

  private function saveTeam($league, $team){
      return 'INSERT IGNORE INTO league_place 
      VALUES("'.$team->team.'", "'.$team->place.'", "'.intval($team->prize).'", "'.$league.'"); ';
  }

   public function save($data, $event){
        $data = json_decode($data);

        $link = $this->getGame($data->league->link);
        if($link != 0){
        $data->league->league_id = $link['league_id'];
        $data->league->game = $link['game'];
        $this->game = $data->league->game;
        $sql = '';
        $sql .= $this->saveLeague($data->league, $event);
        $team_ids = [];

        foreach ($data->matches as $value) {
          $sql .= $this->saveMatch($value, $data->league->league_id);
          array_push($team_ids, $value->team1);
          array_push($team_ids, $value->team2);
        }
        




        $sql .= $this->saveBracket($data->league->link, $data->league->league_id);








        foreach ($data->teams as $value) {
          $sql .= $this->saveTeam($data->league->league_id, $value);
          foreach ($value->players as $player) {
            $sql .= $this->savePlayer($player, $value->team, $data->league->league_id);
          }
        }
        




      $team_ids = array_unique($team_ids);

      $teams_base = (new Query())->select('team_id, updated')->from('teams')->where(['team_id' => $team_ids])->all();

      $teams_save = [];



      foreach ($teams_base as $value) {
        $index = array_search($value['team_id'], $team_ids);
        if($index !== false){
          unset($team_ids[$index]);
        }
        if(strtotime($value['updated']) + (60 * 60 * 24 * 5) <= time()){
          array_push($teams_save, $value['team_id']);
        }
      }



      $teams_save = array_merge($teams_save, $team_ids);
      $a = new TeamData();
        foreach ($teams_save as $team) {
            $a->start($data->league->game, $team);
        }


      Yii::$app->db->createCommand($sql)->execute();
    }else{
        return false;
    }
   }

}
?>

