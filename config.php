<?php

if(!isset($_GET['sendMessage']) && !isset($_POST)){
    header("HTTP/1.1 404 Not Found");
    header("Location: /404");
    exit;
}   

$domain = $_SERVER['HTTP_HOST'];

$webhookUrl = 'https://' . $domain . '/bot.php';

define('webhookUrl',$webhookUrl);

define('botToken','тут API_TOKEN бота');

//Замените 9999999999 на свой chat_id или group_id
define('groupChatid',99999999999);

//Замените mail@gmail.com на почту админа Nethunt
define('mailNethunt','mail@gmail.com');

//Замените mail@gmail.com на почту админа Nethunt
define('nethuntToken','тут API_TOKEN nethunt');

?>