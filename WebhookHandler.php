<?php
function regHandler($cert, $token, $murl)
{
$url = "https://api.telegram.org/bot" . $token . "/setWebhook";
$ch = curl_init();
$optArray = array(
CURLOPT_URL => $url,
CURLOPT_POST => true,
CURLOPT_SAFE_UPLOAD => false,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_POSTFIELDS => array('url' => $murl, 'certificate' => '@' . realpath($cert))
);
curl_setopt_array($ch, $optArray);

$result = curl_exec($ch);
echo "<pre>";
print_r($result);
echo "</pre>";
curl_close($ch);
}

$token = env('BOT_TOKEN');
$path = '/etc/ssl/certs/ssl-cert-snakeoil.pem';
$handlerurl = '/root/cryptobingo-telegram-bot/WebhookHandler.php';

regHandler($path, $token, $handlerurl);
?>
