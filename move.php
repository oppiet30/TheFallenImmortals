<?php
include('db.php');
session_name("fallenimmortals");
session_start();

include('varset.php');

if($_POST['direction'] == "North" && $chary != "100")
$chary ++;

if($_POST['direction'] == "East" && $charx != "100")
$charx ++;

if($_POST['direction'] == "South" && $chary != "1")
$chary --;

if($_POST['direction'] == "West" && $charx != "1")
$charx --;

if($charx == "25" && $chary == "25"){
	$location = "Duel Ground";
}else{
	$location = "Castle";
}

if($_POST['goDuelGround'] == "Yes"){
	$charx = "25";
	$chary = "25";
	$location = "Duel Ground";
}

$query = $conn->prepare("UPDATE characters SET posx=?, posy=?, location=? WHERE username=?");
$query->bind_param("iiss", $charx, $chary, $location, $charname);
$query->execute();

if($_POST['goDuelGround'] == "Yes"){
	include('duelground.php');
}
include('updatestats.php');
?>