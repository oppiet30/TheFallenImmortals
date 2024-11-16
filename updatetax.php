<?php
session_name("icsession");
session_start();
include('db.php');
include('varset.php');
$getchar = mysqli_query($conn, "SELECT * FROM characters WHERE id='".$_SESSION['userid']."'")or die("Not logged in!");
$char = mysqli_fetch_assoc($getchar);

$amount = $_POST['amount'];
if(isset($_POST['amount']) && $amount >= "0")
{
    $getguild = mysqli_query($conn, "SELECT * FROM guilds WHERE name='".$char['guild']."'");
    $guild = mysqli_fetch_assoc($getguild);
    if($amount > 50)
    {
		die('alert(\'Tax cannot be greater than 50%\');');
    }
	if($char['username'] == $guild['leader'] || $char['username'] == $guild['coleader'] || $char['username'] == $guild['captain']){
		
		$message = "updates guild tax to ".$amount."%!";
		$message = "<font color=\'#DD00DD\'><strong>Guild:</strong></font> (<a href=\'javascript:toptell(\"".$char['username']."\");\'><font color=\'#DD00DD\' style=\'text-decoration:none\'>".$charname."</font></a>)<font color=\'#DD00DD\'> ".$message."</font><br />";
		$getmembers = mysqli_query($conn, "SELECT * FROM characters WHERE guild='".$charguild."'");
		while($member = mysqli_fetch_array($getmembers))
		{
			$query = mysqli_query($conn, "INSERT INTO chatroom (`date`, `userlevel`, `username`, `to`, `message`) VALUES ('".$timestamp."', '4', 'PM', '".$member['username']."', '".$message."')");
		}
		$logMessage = "".$char['username']." updated guild tax to ".$amount."%!";
		$letGuildKnow = mysqli_query($conn, "INSERT INTO log (`name`, `message`) VALUES ('".$charguild."', '".$logMessage."')");
		$fixTax = mysqli_query($conn, "UPDATE guilds SET tax='".$amount."' WHERE name='".$char['guild']."'");
		include('updatestats.php');
		print("viewGuild();");
	
	}else{
		die('alert(\'You must be a leader or co-leader to adjust your guild tax.\');');
	}
}else{
	print('alert(\'You make your guild tax negative!\');');
}
?>