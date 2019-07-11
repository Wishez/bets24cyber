<?php

use yii\db\Schema;
use yii\db\Migration;

class m160211_084732_update_user_1 extends Migration
{
    public function up()
    {

        $this->addColumn('{{%user}}', 'avatar', Schema::TYPE_STRING . " AFTER username");
        $this->addColumn('{{%user}}', 'birthday', Schema::TYPE_DATE . " AFTER avatar");

    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'avatar');
        $this->dropColumn('{{%user}}', 'birthday');
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
