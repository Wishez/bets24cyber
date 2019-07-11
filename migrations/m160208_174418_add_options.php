<?php

use yii\db\Schema;
use yii\db\Migration;

class m160208_174418_add_options extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%settings}}', [
            'name' =>  Schema::TYPE_STRING. " primary key UNIQUE",
            'options' => Schema::TYPE_TEXT,
        ], $tableOptions);
//        $this->createIndex('index_name', '{{%settings}}', 'name');
        $user = new \app\models\Settings();
        $user->attributes = ['name'=>'header_menu','options'=>''];
        $user->save();
//
    }

    public function down()
    {
        $this->dropTable('{{%settings}}');

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
