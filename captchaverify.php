<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include('varset.php');
$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);

  require_once('recaptchalib.php');
  $privatekey = "6Ld9zssSAAAAACUwfuV6pDnpOt60SIP57hu1xD-i";
  $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
  $time = time();
  if($char['captcha_time_limit'] != "0" && $char['captcha_time_limit'] < $time){
	  
	print("alert('You have taken too long. You are Suspended for 12 hours!');");
	$reasonSuspend = "Failed reCaptcha";
	$timeSuspended = 43200 + time();
	$updateTheDumbass = db_query("UPDATE characters SET lastactive='0', status='Suspended', endsuspend=?, reason=?, captcha='Inactive', captcha_time_limit='0' WHERE username=?", [$timeSuspended, $reasonSuspend, $char['username']]);
	
	$suspendmessage = "<b><font color=\'#DD00DD\'>Player ".$char['username']." has been suspended for 12 hours! Reason: Failed reCAPTCHA security test.</font></b><br />";
	$date = date('ymdHi');
	$query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`)VALUES (?, '3', ?, ?, 'Chatroom')", [$date, $char['username'], $suspendmessage]);
	
	die();
				
  } elseif (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
		 $timeLeft = $char['captcha_time_limit'] - time();
		 print("alert('Failed try again. ".$timeLeft." seconds left!');");
		 print("showRecaptcha('recaptcha_div');");
  } elseif($resp->is_valid) {
	  	 $random = rand(1,5);
		 $gold = $random * $char['level'];
    	 print("fillDiv('displayArea', 'You get ".number_format($gold)." gold for passing the test!');");
		 db_query("UPDATE characters SET gold=gold+?, captcha='Inactive', captcha_time_limit='0' WHERE username=?", [$gold, $char['username']]);
  }
  ?>