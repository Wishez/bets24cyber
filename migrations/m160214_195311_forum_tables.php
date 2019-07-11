<?php

use yii\db\Schema;
use yii\db\Migration;

class m160214_195311_forum_tables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%forum_category}}', [
            'id_fcategory' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING,
            'id_owner_category' => Schema::TYPE_INTEGER,
        ], $tableOptions);

        $this->createTable('{{%forum_topic}}', [
            'id_ftopic' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING,
            'id_fcategory' => Schema::TYPE_INTEGER,
            'id_user_owner' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'views' => Schema::TYPE_INTEGER,
            'enable' => Schema::TYPE_INTEGER,
            'visible' => Schema::TYPE_INTEGER
        ], $tableOptions);

        $this->createTable('{{%forum_post}}', [
            'id_fpost' => Schema::TYPE_PK,
            'id_ftopic' => Schema::TYPE_INTEGER,
            'id_user' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'description' => Schema::TYPE_TEXT,
        ], $tableOptions);

        $this->createTable('{{%forum_like}}', [
            'id_flike' => Schema::TYPE_PK,
            'id_fpost' => Schema::TYPE_INTEGER,
            'count' => Schema::TYPE_INTEGER,
            'forum_likes_user_list' => Schema::TYPE_TEXT,
        ], $tableOptions);


        $this->createIndex('category_to_category','{{%forum_category}}',['id_owner_category']);
        $this->createIndex('topic_to_user','{{%forum_topic}}',['id_user_owner']);
        $this->createIndex('topic_to_category','{{%forum_topic}}',['id_fcategory']);
        $this->createIndex('post_to_user','{{%forum_post}}',['id_user']);
        $this->createIndex('post_to_topic','{{%forum_post}}',['id_ftopic']);
        $this->createIndex('like_to_post','{{%forum_like}}',['id_fpost']);

        $this->addForeignKey('category_to_category','{{%forum_category}}',['id_owner_category'],'{{%forum_category}}',['id_fcategory']);
        $this->addForeignKey('topic_to_user','{{%forum_topic}}',['id_user_owner'],'{{%user}}',['id']);
        $this->addForeignKey('post_to_user','{{%forum_post}}',['id_user'],'{{%user}}',['id']);
        $this->addForeignKey('topic_to_category','{{%forum_topic}}',['id_fcategory'],'{{%forum_category}}',['id_fcategory']);
        $this->addForeignKey('post_to_topic','{{%forum_post}}',['id_ftopic'],'{{%forum_topic}}',['id_ftopic']);
        $this->addForeignKey('like_to_post','{{%forum_like}}',['id_fpost'],'{{%forum_post}}',['id_fpost']);




    }

    public function down()
    {
        $this->dropTable('{{%forum_like}}');
        $this->dropTable('{{%forum_post}}');
        $this->dropTable('{{%forum_topic}}');
        $this->dropTable('{{%forum_category}}');


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
