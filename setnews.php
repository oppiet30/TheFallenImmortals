<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include('varset.php');
include('functions.php');

$getguild = db_query("SELECT * FROM guilds WHERE name=?", [$char['guild']]);
$guild = db_fetch_assoc($getguild);
if($charname == $guild['leader'] || $charname == $guild['coleader'])
{
	$news = htmlentities((carriage($_POST['news'])));

	$setguild = db_query("UPDATE guilds SET news=? WHERE name=?", [$news, $char['guild']]);
	print("viewGuild();");
}
?>