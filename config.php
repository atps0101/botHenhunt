<?php

if(!isset($_GET['sendMessage']) && !isset($_POST)){
    header("HTTP/1.1 404 Not Found");
    header("Location: /404");
    exit;
}   

$domain = $_SERVER['HTTP_HOST'];

$webhookUrl = 'https://' . $domain . '/bot.php';

define('webhookUrl',$webhookUrl);

define('botToken','хххххххххххххххххххххххххххххххх');

define('groupChatid',ххххххххххххххх);

//Замените mail@gmail.com на почту админа Nethunt
define('mailNethunt','mail@gmail.com');

//Замените mail@gmail.com на почту админа Nethunt
define('nethuntToken','ххххххххххххххххххххххххххххххххххх');

?>