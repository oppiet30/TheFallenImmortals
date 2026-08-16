<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include('varset.php');
include_once('functions.php');

if(isset($_POST['guildname']) && $_POST['guildname'] != "Select Guild")
{
	$guildname = $_POST['guildname'];
	$getguild = $conn->prepare("SELECT * FROM guilds WHERE id=?");
	$getguild->bind_param("i", $guildname);
	$getguild->execute();
	$guild = $getguild->get_result()->fetch_assoc();

	$getmembers = $conn->prepare("SELECT * FROM characters WHERE guild=?");
	$getmembers->bind_param("s", $guild['name']);
	$getmembers->execute();
	$getmembersResult = $getmembers->get_result();
	$members = $getmembersResult->num_rows;

	while($member = $getmembersResult->fetch_array())
	{
		$totalbonus += floor($member['level'] / "100");
	}

	$data = "Members: ".number_format($members)."";
	$data .= "<br />Leader: <a href=\'javascript:toptell(\"".$guild['leader']."\");\'>".$guild['leader']."</a>";
	$data .= "<br />Co-Leader: <a href=\'javascript:toptell(\"".$guild['coleader']."\");\'>".$guild['coleader']."</a>";
	$data .= "<br />Captain: <a href=\'javascript:toptell(\"".$guild['captain']."\");\'>".$guild['captain']."</a>";
	$data .= "<br />Process: ".$guild['accept'];
	$data .= "<br />Bonus: ".number_format($totalbonus);
	$data .= "<br />Exp: ".$guild['exp']."%";
	$data .= "<br />Gold: ".$guild['gold']."%";
	$data .= "<br />Item Drops: ".$guild['itemdrop']."%";
	$data .= "<br />Item Boost: ".$guild['itemboost']."%";

	print("fillDiv('guildinfo','".$data."');");
}
else
{
	print("fillDiv('guildinfo','<u><em>Guild information is loaded here.</em></u>');");
}
?>