<?php

$headers = array('Content-Type: application/json');

$bot_token = getenv("BOT_TOKEN");
$channel = getenv("CHANNEL_ID");

date_default_timezone_set("Asia/Tehran");
$time = date("H:i");
$date = date("Y/m/d");

$sources = [
    "https://t.me/s/iproxy_up",
    "https://t.me/s/MTPro_Gold",
    "https://t.me/s/MTProto_Channel",
    "https://t.me/s/ProxyMTProto"
];

$proxies = [];

foreach ($sources as $url) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => "Mozilla/5.0"
    ]);
    $html = curl_exec($curl);
    curl_close($curl);

    $html = str_replace("@iProxy_up", $channel, $html); // تغییر تبلیغ
    preg_match_all('/https:\/\/t\.me\/proxy\?server=[^"\s]+/', $html, $matches);
    if (!empty($matches[0])) {
        $proxies = array_merge($proxies, $matches[0]);
    }
}

if (empty($proxies)) {
    exit("❌ No proxy found.");
}

shuffle($proxies);
$link = $proxies[0];

$msg = "🛰 <b>MTProto Proxy Live</b>\n";
$msg .= "━━━━━━━━━━━━━━━━━━━━━━\n";
$msg .= "📡 اتصال پایدار | 🔒 رمزگذاری کامل | 🚀 بدون افت سرعت\n";
$msg .= "⏰ زمان: {$time}   📅 تاریخ: {$date}\n";
$msg .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
$msg .= "<a href='{$link}'>💠 اتصال فوری 💠</a>\n\n";
$msg .= "🌀 پروکسی‌های بیشتر در: {$channel}";

$data = array(
    'chat_id' => $channel,
    'text' => $msg,
    'parse_mode' => 'HTML',
    'disable_web_page_preview' => false
);

$ch = curl_init("https://api.telegram.org/bot{$bot_token}/sendMessage");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);
curl_close($ch);

echo "✅ Proxy sent!";
?>
