<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include('varset.php');
$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);

$amount = $_POST['amount'];
if(isset($_POST['amount']) && $amount >= "0")
{
    $getguild = db_query("SELECT * FROM guilds WHERE name=?", [$char['guild']]);
    $guild = db_fetch_assoc($getguild);
    if($amount > 50)
    {
		die('alert(\'Tax cannot be greater than 50%\');');
    }
	if($char['username'] == $guild['leader'] || $char['username'] == $guild['coleader'] || $char['username'] == $guild['captain']){
		
		$message = "updates guild tax to ".$amount."%!";
		$message = "<font color=\'#DD00DD\'><strong>Guild:</strong></font> (<a href=\'javascript:toptell(\"".$char['username']."\");\'><font color=\'#DD00DD\' style=\'text-decoration:none\'>".$charname."</font></a>)<font color=\'#DD00DD\'> ".$message."</font><br />";
		$getmembers = db_query("SELECT * FROM characters WHERE guild=?", [$charguild]);
		while($member = db_fetch_array($getmembers))
		{
			$query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `to`, `message`) VALUES (?, '4', 'PM', ?, ?)", [$timestamp, $member['username'], $message]);
		}
		$logMessage = "".$char['username']." updated guild tax to ".$amount."%!";
		$letGuildKnow = db_query("INSERT INTO log (`name`, `message`) VALUES (?, ?)", [$charguild, $logMessage]);
		$fixTax = db_query("UPDATE guilds SET tax=? WHERE name=?", [$amount, $char['guild']]);
		include('updatestats.php');
		print("viewGuild();");
	
	}else{
		die('alert(\'You must be a leader or co-leader to adjust your guild tax.\');');
	}
}else{
	print('alert(\'You make your guild tax negative!\');');
}
?>