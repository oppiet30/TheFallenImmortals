<?php
session_name("icsession");
session_start();
include('db.php');
include('varset.php');
include('functions.php');

$getguild = mysqli_query("SELECT * FROM guilds WHERE name='".$char['guild']."'");
$guild = mysqli_fetch_assoc($getguild);
if($charname == $guild['leader'] || $charname == $guild['coleader'])
{
	$news = htmlentities((carriage($_POST['news'])));

	$setguild = mysqli_query("UPDATE guilds SET news='".$news."' WHERE name='".$char['guild']."'");
	print("viewGuild();");
}
?>