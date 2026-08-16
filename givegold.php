<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include('active.php');
$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute();
$char = $getchar->get_result()->fetch_assoc();
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
			$findCharacter = $conn->prepare("SELECT * FROM characters WHERE username=?");
			$findCharacter->bind_param("s", $to);
			$findCharacter->execute();
			$findCharacterResult = $findCharacter->get_result();
			$countNumRows = $findCharacterResult->num_rows;
			if($countNumRows == 0)
			{
				$display .= "<center><font color=\'red\'>No such character.</font></center>";
			}else{
				$to = $findCharacterResult->fetch_assoc();
				$updateTo = $conn->prepare("UPDATE characters SET gold=gold-? WHERE username=?");
				$updateTo->bind_param("is", $amount, $char['username']);
				$updateTo->execute() or die("alert('Unable to remove gold. Tell admin.');");
				$updateTo = $conn->prepare("UPDATE characters SET gold=gold+? WHERE username=?");
				$updateTo->bind_param("is", $amount, $to['username']);
				$updateTo->execute() or die("alert('Unable to add gold. Tell admin.');");
				$display .= "<center><font color=\'green\'>You have given ".$to['username']." ".number_format($amount)." gold from your hand!</font></center>";
				$datestamp = date("H:i:s");
				$message = "<a href=\'javascript:toptell(\"".$char['username']."\");\'><font color=\'#FF7700\' style=\'text-decoration:none\'>".$char['username']."</font></a><font color=\'#FF7700\'> has given you ".number_format($amount)." gold from their hand.</font><br />";
				$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `to`, `message`) VALUES (?, '4', 'PM', ?, ?)");
				$query->bind_param("sss", $datestamp, $to['username'], $message);
				$query->execute() or die("alert('Unable to insert chat log. Tell the admin!');");
			}
		}
	}

}

print("fillDiv('displayArea','".$display."');");
include('updatestats.php');
?>