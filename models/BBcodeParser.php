<?php
namespace app\models;

use app\models\StringParser_BBCode;
use nill\forum\phpBB;
use nill\forum\phpbbClass;
use parse_message;

class BBcodeParser extends phpbbClass
{
    public function __construct($php_extension = "php")
    {

//        global $phpbb_root_path, $phpEx, $db, $config, $user, $auth, $cache, $template;
//        define('IN_PHPBB', true);
        $phpbb_root_path = $_SERVER['DOCUMENT_ROOT']. '/web/forum/';
        global $phpbb_root_path,$php_extension;
//        $phpEx = $php_extension;
        parent::__construct($phpbb_root_path, $php_extension = "php");
    }

    public function Decode($text)
    {
        require_once($_SERVER['DOCUMENT_ROOT']. '/web/forum/' . "includes/message_parser.php");
        $a = new parse_message();
        $a->parse_message($text);
    }
}

//web/forum/includes/message_parser.php
