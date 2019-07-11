<?php
namespace app\models;
use yii\db\Query;
use Yii;
use app\models\LoadData;
class GetPlayers extends LoadData{
    public function run(){
        $this->getDataWiki();
    }
}


?>