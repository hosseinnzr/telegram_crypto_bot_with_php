<?php
require 'vendor/autoload.php';
include 'data.php';

function Usdt_price($data) {    
    if (!$data || !isset($data["results"])) return "error";

    foreach ($data["results"] as $market) {
        if ($market["code"] === "USDT_IRT") {
            $tehter = array(
                "now" => number_format($market["price"]), 
                "max" => number_format($market["price_info"]["max"]), 
                "min" => number_format($market["price_info"]["min"])
            );
            return $tehter;
        }
    }
    return "unknown";
}

// +_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_ //
function Btc_IR_price($data) {    
    if (!$data || !isset($data["results"])) return "error";

    foreach ($data["results"] as $market) {
        if ($market["code"] === "BTC_IRT") {
            return number_format($market["price"]);
        }
    }
    return "unknown";
}

function Eth_IR_price($data) {    
    if (!$data || !isset($data["results"])) return "error";

    foreach ($data["results"] as $market) {
        if ($market["code"] === "ETH_IRT") {
            return number_format($market["price"]);
        }
    }
    return "unknown";
}

function Sol_IR_price($data) {    
    if (!$data || !isset($data["results"])) return "error";

    foreach ($data["results"] as $market) {
        if ($market["code"] === "SOL_IRT") {
            return number_format($market["price"]);
        }
    }
    return "unknown";
}

function Bnb_IR_price($data) {    
    if (!$data || !isset($data["results"])) return "error";

    foreach ($data["results"] as $market) {
        if ($market["code"] === "BNB_IRT") {
            return number_format($market["price"]);
        }
    }
    return "unknown";
}
// +_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_ //

function Btc_USDT_price($data){
    if (!$data || !isset($data["results"])) return "error";

    foreach ($data["results"] as $market) {
        if ($market["code"] === "BTC_USDT") {
            return number_format($market["price"]);
        }
    }
    return "unknown";
}

function Eth_USDT_price($data){
    if (!$data || !isset($data["results"])) return "error";

    foreach ($data["results"] as $market) {
        if ($market["code"] === "ETH_USDT") {
            return number_format($market["price"]);
        }
    }
    return "unknown";
}

function Sol_USDT_price($data){
    if (!$data || !isset($data["results"])) return "error";

    foreach ($data["results"] as $market) {
        if ($market["code"] === "SOL_USDT") {
            return number_format($market["price"]);
        }
    }
    return "unknown";
}

function Bnb_USDT_price($data){
    if (!$data || !isset($data["results"])) return "error";

    foreach ($data["results"] as $market) {
        if ($market["code"] === "BNB_USDT") {
            return number_format($market["price"]);
        }
    }
    return "unknown";
}


// ==================================== //
function fetchData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($response === false || $httpCode !== 200) {
        curl_close($ch);
        return false;
    }

    curl_close($ch);
    $decodedResponse = json_decode($response, true);

    if (!is_array($decodedResponse)) {
        return false;
    }

    return $decodedResponse;
}

function sendTelegramMessage($message) {
    $botToken = "your telegram chanel token";
    $chatId = "your telegram channel chatId"; // like: -12345678910112
    
    $url = "https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=" . urlencode($message) . "&parse_mode=HTML";

    $curl = new \Curl\Curl();

    $curl->setOpt(CURLOPT_PROXY, 'http://127.0.0.1:10809');
    
    $curl->get($url);
    
    if ($curl->error) {
        echo "send error!";
    } else {
        echo "send successful!";
    }
    
    $curl->close();
}
// ==================================== //


$data = false;
while(!$data) {
    $data = fetchData("https://api.bitpin.ir/v1/mkt/markets/");
}

$gregorianDate = date('Y-m-d');
$date = getFormattedJalaliDate($gregorianDate);

$usdtPrice = Usdt_price($data)['now'];
$usdtMaxPrice = Usdt_price($data)['max'];
$usdtMinPrice = Usdt_price($data)['min'];

// TOMAN
$btcIrPrice = Btc_IR_price($data) ;
$ethIrPrice = Eth_IR_price($data);
$solIrPrice = Sol_IR_price($data);
$bnbIrPrice = Bnb_IR_price($data);

// USDT
$btcUsdtPrice = Btc_USDT_price($data);
$ethUsdtPrice = Eth_USDT_price($data);
$solUsdtPrice = Sol_USDT_price($data);
$bnbUsdtPrice = Bnb_USDT_price($data);

$message = "$date \n\n";

$message .= "<b>📊 تتر (تومان) :</b>";
$message .= "<blockquote>";
$message .= "📈 تتر - بیشترین : $usdtMaxPrice \n";
$message .= "🔹 تتر : $usdtPrice \n";
$message .= "📉 تتر - کمترین : $usdtMinPrice \n";
$message .= "</blockquote>";

$message .= "<b></b>\n";

$message .= "<b>📊 ارز دیجیتال (تومان) :</b>";
$message .= "<blockquote>";
$message .= "🔸 بیت‌کوین : $btcIrPrice \n";
$message .= "🔸 اتریوم : $ethIrPrice \n";
$message .= "🔸 سولانا : $solIrPrice \n";
$message .= "🔸 بایننس کوین : $bnbIrPrice \n";
$message .= "</blockquote>";

$message .= "<b></b>\n";

$message .= "<b>📊 ارز دیجیتال (دلار) :</b>";
$message .= "<blockquote>";
$message .= "🔹 بیت‌کوین : $btcUsdtPrice \n";
$message .= "🔹 اتریوم : $ethUsdtPrice \n";
$message .= "🔹 سولانا : $solUsdtPrice \n";
$message .= "🔹 بایننس کوین : $bnbUsdtPrice \n";
$message .= "</blockquote>";

sendTelegramMessage($message);
