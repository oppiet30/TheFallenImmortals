<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include('varset.php');
include_once('functions.php');

$getguild = $conn->prepare("SELECT * FROM guilds WHERE name=?");
$getguild->bind_param("s", $char['guild']);
$getguild->execute();
$guild = $getguild->get_result()->fetch_assoc();
if($charname == $guild['leader'] || $charname == $guild['coleader'])
{
	$news = htmlentities((carriage($_POST['news'])));

	$setguild = $conn->prepare("UPDATE guilds SET news=? WHERE name=?");
	$setguild->bind_param("ss", $news, $char['guild']);
	$setguild->execute();
	print("viewGuild();");
}
?>