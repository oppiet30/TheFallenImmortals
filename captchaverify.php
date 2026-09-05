<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include('varset.php');
$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);

$captchaConfig = include 'captcha-config.php';
$secretKey = $captchaConfig['secret_key'] ?? '';
$time = time();

$unconfigured = ($secretKey === '' || $secretKey === 'YOUR_RECAPTCHA_SECRET_KEY');

if ($unconfigured) {
    $random = rand(1,5);
    $gold = $random * $char['level'];
    print("fillDiv('displayArea', 'You get ".number_format($gold)." gold for passing the test!');");
    db_query("UPDATE characters SET gold=gold+?, captcha='Inactive', captcha_time_limit='0' WHERE username=?", [$gold, $char['username']]);
    die();
}

$responseToken = (string)($_POST['g-recaptcha-response'] ?? '');

if ($responseToken === '') {
    print("alert('Please complete the captcha first.');");
    print("showRecaptcha('recaptcha_div');");
    die();
}

if ($char['captcha_time_limit'] != "0" && $char['captcha_time_limit'] < $time) {

    print("alert('You have taken too long. You are Suspended for 12 hours!');");
    $reasonSuspend = "Failed reCaptcha";
    $timeSuspended = 43200 + time();
    $updateTheDumbass = db_query("UPDATE characters SET lastactive='0', status='Suspended', endsuspend=?, reason=?, captcha='Inactive', captcha_time_limit='0' WHERE username=?", [$timeSuspended, $reasonSuspend, $char['username']]);

    $suspendmessage = "<b><font color=\'#DD00DD\'>Player ".$char['username']." has been suspended for 12 hours! Reason: Failed reCAPTCHA security test.</font></b><br />";
    $date = date('ymdHi');
    $query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`)VALUES (?, '3', ?, ?, 'Chatroom')", [$date, $char['username'], $suspendmessage]);

    die();
}

$ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'secret' => $secretKey,
        'response' => $responseToken,
        'remoteip' => $_SERVER['REMOTE_ADDR'],
    ]),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
]);
$response = curl_exec($ch);
curl_close($ch);
$verify = json_decode((string)$response, true);

if (!is_array($verify) || (($verify['success'] ?? false) !== true)) {
    $timeLeft = $char['captcha_time_limit'] - time();
    print("alert('Failed try again. ".$timeLeft." seconds left!');");
    print("showRecaptcha('recaptcha_div');");
} elseif (($verify['success'] ?? false) === true) {
    $random = rand(1,5);
    $gold = $random * $char['level'];
    print("fillDiv('displayArea', 'You get ".number_format($gold)." gold for passing the test!');");
    db_query("UPDATE characters SET gold=gold+?, captcha='Inactive', captcha_time_limit='0' WHERE username=?", [$gold, $char['username']]);
}
?>