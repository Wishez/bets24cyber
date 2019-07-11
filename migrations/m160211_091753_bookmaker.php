<?php

use yii\db\Schema;
use yii\db\Migration;

class m160211_091753_bookmaker extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bookmaker}}', [
            'id_bmaker' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING,
            'desc' => Schema::TYPE_TEXT,
            'logo' => Schema::TYPE_STRING,
            'link' => Schema::TYPE_STRING,
            'visible' => Schema::TYPE_INTEGER,
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%bookmaker}}');

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
