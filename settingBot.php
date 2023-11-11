<?php 
require_once "config.php";
require_once "vendor/autoload.php";

$telegram = new \TelegramBot\Api\BotApi(botToken);

$webhook = $telegram->setWebhook(webhookUrl);

if($webhook != true){
    $telegram->deleteWebhook();
    $telegram->setWebhook(webhookUrl);
}

$webhookInfo = $telegram->getWebhookInfo();

if(!isset($_GET['sendMessage']) && !isset($_POST)){
    header("HTTP/1.1 404 Not Found");
    header("Location: /404");
    exit;
}   


?>