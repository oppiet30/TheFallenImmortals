<?php
session_name("fallenimmortals");
session_start();
include('db.php');
$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar)or die();
$data .= "";


if($_POST['change'] == "yes" && $char['gold'] >= "100000000" && $char['level'] >= "5000"){
	$CharLevel = $char['level'];
	$CharClass = $char['class'];
	$CharExpacq = $char['expacq'];
	$CharExpreq = $char['expreq'];
	$CharBlood = $char['blood'];
	
	$findSecondClassInTheCookieJar = db_query("SELECT * FROM secondclass WHERE username=?", [$char['username']]);
	$sClass = db_fetch_assoc($findSecondClassInTheCookieJar);
	$newClass = $sClass['class'];
	if($sClass['level'] == "1"){
		$messagechat = "<strong><font color=\'#660077\'><b>".$char['username']."</b> has switched their class to <b>".$sClass['class']."</b> for the first time. Good luck!.</font></strong><br />";
        $query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')", [$date, $char['username'], $messagechat]);
	}
	$updateCharacter = db_query("UPDATE characters SET gold=gold-'100000000' WHERE id=?", [$char['id']]);
	$changeClass = db_query("UPDATE characters SET level=?, class=?, expacq=?, expreq=?, blood=? WHERE id=?", [$sClass['level'], $sClass['class'], $sClass['expacq'], $sClass['expreq'], $sClass['blood'], $char['id']]);
	$storeClass = db_query("UPDATE secondclass SET level=?, class=?, expacq=?, expreq=?, blood=? WHERE username=?", [$CharLevel, $CharClass, $CharExpacq, $CharExpreq, $CharBlood, $char['username']]);
	$data .= "You have changed your class to ".$newClass."!";
	
}else{
	die("alert('Lack of requirements.')");
}

print("fillDiv('displayArea','".$data."');");
include('updatestats.php');
?>