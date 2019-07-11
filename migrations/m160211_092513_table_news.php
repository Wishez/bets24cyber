<?php

use yii\db\Schema;
use yii\db\Migration;

class m160211_092513_table_news extends Migration
{
    public function up()
    {


        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%news}}', [
            'id_news' => Schema::TYPE_PK,
            'title' => Schema::TYPE_TEXT,
            'desc' => Schema::TYPE_TEXT,
            'text' => Schema::TYPE_TEXT,
            'img' => Schema::TYPE_STRING,
            'id_user' => Schema::TYPE_INTEGER,
            'id_category' => Schema::TYPE_INTEGER,
            'id_bmaker' => Schema::TYPE_INTEGER,
            'show_in_footer' => Schema::TYPE_INTEGER,
            'created_at'=>Schema::TYPE_INTEGER,
            'updated_at'=>Schema::TYPE_INTEGER,
            'sort'=>Schema::TYPE_INTEGER

        ], $tableOptions);

        $this->createIndex('news_to_user','{{%news}}',['id_user']);
        $this->createIndex('news_to_cat_idx','{{%news}}',['id_category']);
        $this->createIndex('news_to_bookmaker','{{%news}}',['id_bmaker']);
        $this->addForeignKey('news_to_user','{{%news}}',['id_user'],'{{%user}}',['id']);
        $this->addForeignKey('news_to_cat','{{%news}}',['id_category'],'{{%category}}',['id_category']);
        $this->addForeignKey('news_to_bookmaker','{{%news}}',['id_bmaker'],'{{%bookmaker}}',['id_bmaker']);

    }

    public function down()
    {

        $this->dropTable('{{%news}}');

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
