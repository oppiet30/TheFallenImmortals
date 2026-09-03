<?php
session_name("icsession");
session_start();
include('db.php');
include('active.php');
$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);
$display = "";

if(isset($_POST['toUsername']) && isset($_POST['giveAmount']))
{
	
	$to = $_POST['toUsername'];
	$amount = $_POST['giveAmount'];
	if(!ctype_digit($amount)){
		$display .= "<center><font color=\'red\'>Invalid amount.</font></center>";
		die();
	}else{
		if($char['gold'] < $amount)
		{
			$display .= "<center><font color=\'red\'>Not enough gold.</font></center>";
			die();
		}else{
			$findCharacter = db_query("SELECT * FROM characters WHERE username=?", [$to]);
			$countNumRows = db_num_rows($findCharacter);
			if($countNumRows == 0)
			{
				$display .= "<center><font color=\'red\'>No such character.</font></center>";
			}else{
				$to = db_fetch_assoc($findCharacter);
				$updateTo = db_query("UPDATE characters SET gold=gold-? WHERE username=?", [$amount, $char['username']]);
				$updateTo = db_query("UPDATE characters SET gold=gold+? WHERE username=?", [$amount, $to['username']]);
				$display .= "<center><font color=\'green\'>You have given ".$to['username']." ".number_format($amount)." gold from your hand!</font></center>";
				$datestamp = date("H:i:s");
				$message = "<a href=\'javascript:toptell(\"".$char['username']."\");\'><font color=\'#FF7700\' style=\'text-decoration:none\'>".$char['username']."</font></a><font color=\'#FF7700\'> has given you ".number_format($amount)." gold from their hand.</font><br />";
				$query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `to`, `message`) VALUES (?, '4', 'PM', ?, ?)", [$datestamp, $to['username'], $message]);
			}
		}
	}

}

print("fillDiv('displayArea','".$display."');");
include('updatestats.php');
?>