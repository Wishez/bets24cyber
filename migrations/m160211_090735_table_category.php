<?php

use yii\db\Schema;
use yii\db\Migration;

class m160211_090735_table_category extends Migration
{
    public function up()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%category}}', [
            'id_category' => Schema::TYPE_PK,
            'name' => Schema::TYPE_TEXT,
        ], $tableOptions);
        $this->insert('{{%category}}', ['name' => 'DOTA']);
        $this->insert('{{%category}}', ['name' => 'CS']);

    }

    public function down()
    {
        $this->dropTable('{{%category}}');

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