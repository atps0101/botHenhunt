<?php 

require_once "vendor/autoload.php";

require_once "config.php";

if(!isset($_GET['sendMessage']) && !isset($_POST)){
    header("HTTP/1.1 404 Not Found");
    header("Location: /404");
    exit;
}   



try {
    $admin = true;

    $bot = new \TelegramBot\Api\Client(botToken);

    $bot->command('info', function ($message) use ($bot) {
        $keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("/info")), true);

        $info = getInfo();

        $costs = getCosts();

        $costs_FB = is_numeric($costs['costs_FB']) ? floatval($costs['costs_FB']) : 0;
        $costs_google = is_numeric($costs['costs_google']) ? floatval($costs['costs_google']) : 0;
        $total_costs = $costs_FB + $costs_google;


        $telegramMessage = buildTelegramMessage($info, $costs, $total_costs);
            
        $bot->sendMessage(groupChatid, $telegramMessage, null, false, null, $keyboard);
    });

    
    $bot->run();

} catch (\TelegramBot\Api\Exception $e) {
    $e->getMessage();
}


function buildTelegramMessage($info, $costs,$total_costs) {

    $leadsBySource = [];

    // Группируем лиды по utm_источнику
    foreach ($info as $item) {
        $source = !empty($item['fields']['utm_source']) ? $item['fields']['utm_source'] : 'SEO';
        if (!isset($leadsBySource[$source])) {
            $leadsBySource[$source] = [
                'leads' => 0,
                'deals' => 0,
                'costs' => [],
            ];
        }
    
        if ($item['fields']['Stage'] === "Sent Offers-Analysis of the object") {
            $leadsBySource[$source]['deals']++;
        }
    
        $leadsBySource[$source]['leads']++;
        $leadsBySource[$source]['costs'][] = (float)$item['fields']['costs'];
    }
    

    $totalLeads = array_sum(array_column($leadsBySource, 'leads'));
    $totalDeals = array_sum(array_column($leadsBySource, 'deals'));

    $message = "Общее кол-во Лидов: $totalLeads\n";
    $message .= "Сконвертировано в Сделку: $totalDeals\n";
    $message .= "Затраты: $total_costs $\n\n";

    $costs = array(
        "FB" => $costs['costs_FB'],
        "google_ads" => $costs['costs_google']
    );
    
    foreach ($leadsBySource as $source => $data) {

        $cost = isset($costs[$source]) ? $costs[$source] : 0;

        $message .= "$source: {$data['leads']} Лидов из которых {$data['deals']} в Сделку; {$cost} $\n";
    }

    return $message;
}


    function getInfo(){
        //Вместо хххххххххххххххххххххххх folderId
        $api_url = 'https://nethunt.com/api/v1/zapier/triggers/new-record/хххххххххххххххххххххххх?since=' . urlencode(date('Y-m-d\TH:i:s.000\Z', strtotime('yesterday')));

        $credentials = base64_encode(mailNethunt . ':' . nethuntToken);

        $ch = curl_init($api_url);
        $headers = array(
            'Authorization: Basic ' . $credentials,
            'Content-Type: application/json', 
        );

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        curl_close($ch);

        $results = json_decode($response, true);

        $currentDate = new DateTime();

        $today = $currentDate->format('Y-m-d\TH:i:s.000\Z');

        $filteredResults = array_filter($results, function ($item) use ($today) {
            return substr($item['createdAt'], 0, 10) !== substr($today , 0, 10);
        });

        return $filteredResults;

    }


    function getCosts(){

        //$api_url = 'https://nethunt.com/api/v1/zapier/triggers/writable-folder'; - вывод папок, которые доступны в Nethunt

        //https://nethunt.com/integration-api тут докс, как получить recordId узнаете тут

        $folderId = 'хххххххххххххххххххххххх';
        $recordId = 'хххххххххххххххххххххххх';

        $api_url = 'https://nethunt.com/api/v1/zapier/searches/find-record/'.$folderId.'?recordId='.$recordId;
        
        $credentials = base64_encode(mailNethunt . ':' . nethuntToken);
    
        $ch = curl_init($api_url);
        $headers = array(
            'Authorization: Basic ' . $credentials,
            'Content-Type: application/json', 
        );
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
        $response = curl_exec($ch);
    
        curl_close($ch);
    
        $results = json_decode($response, true);
    
        return $results[0]['fields'];

    }

    $postData = file_get_contents('php://input');

    //botLog($postData);

    function botLog($data){
        $filePath = 'post_data.txt';

        $file = fopen($filePath, 'a'); 
    
        if ($file) {
    
            fwrite($file, $data . "\n");

            fclose($file);
        } 
    
    }

    if(isset($_GET['sendMessage']) && $_GET['sendMessage'] == "MK2Cu528ze"){
        $bot = new \TelegramBot\Api\BotApi(botToken);

        $info = getInfo();

        $costs = getCosts();

        $costs_FB = is_numeric($costs['costs_FB']) ? floatval($costs['costs_FB']) : 0;
        $costs_google = is_numeric($costs['costs_google']) ? floatval($costs['costs_google']) : 0;
        $total_costs = $costs_FB + $costs_google;

        $telegramMessage = buildTelegramMessage($info, $costs, $total_costs);

        $keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("/info")), true); 
        $bot->sendMessage(groupChatid, $telegramMessage, null, false, null, $keyboard);

    }


    

?>