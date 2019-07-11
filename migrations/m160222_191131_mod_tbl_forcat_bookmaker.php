<?php

use yii\db\Schema;
use yii\db\Migration;

class m160222_191131_mod_tbl_forcat_bookmaker extends Migration
{
    public function up()
    {
        $this->dropForeignKey('news_to_bookmaker','{{%news}}');
        $this->renameColumn('{{%bookmaker}}','id_bookmaker','id_bmaker');
        $this->renameColumn('{{%news}}','id_bookmaker','id_bmaker');
        $this->addForeignKey('news_to_bookmaker','{{%news}}',['id_bmaker'],'{{%bookmaker}}',['id_bmaker']);
    }

    public function down()
    {
        $this->dropForeignKey('news_to_bookmaker','{{%news}}');
        $this->renameColumn('{{%news}}','id_bmaker','id_bookmaker');
        $this->renameColumn('{{%bookmaker}}','id_bmaker','id_bookmaker');
        $this->addForeignKey('news_to_bookmaker','{{%news}}',['id_bookmaker'],'{{%bookmaker}}',['id_bookmaker']);

        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
