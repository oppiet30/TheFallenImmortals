<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include('varset.php');

$getguild = $conn->prepare("SELECT * FROM guilds WHERE name=?");
$getguild->bind_param("s", $charguild);
$getguild->execute();
$guild = $getguild->get_result()->fetch_assoc();
if($charname == $guild['leader'] || $charname == $guild['coleader'])
{
	$news = str_replace("[br]", "\\r", addslashes($guild['news']));
	$data .= "<textarea id=\'guildnews\' rows=\'12\' cols=\'70\'>".$news."</textarea><input type=\'button\' onclick=\'javascript: setNews();\' value=\'Modify\' />";
	print("fillDiv('guildsettings','".$data."');");
}
?>