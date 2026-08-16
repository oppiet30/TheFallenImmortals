<?php
session_name("fallenimmortals");
session_start();
include('db.php');
$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute();
$char = $getchar->get_result()->fetch_assoc();

if($_POST['charrequesting'] != NULL){
	$currentDuel = $conn->prepare("SELECT * FROM duelground WHERE tousername=? OR fromusername=?");
	$currentDuel->bind_param("ss", $char['username'], $char['username']);
	$currentDuel->execute();
	if($currentDuel->get_result()->num_rows >= 1){
		print("alert('You already have a pending duel request. Check the chatroom.');");
	}else{
		$findOponent = $conn->prepare("SELECT * FROM characters WHERE username=?");
		$findOponent->bind_param("s", $_POST['charrequesting']);
		$findOponent->execute() or die($conn->error);
		$findOponentResult = $findOponent->get_result();
		if($findOponentResult->num_rows == 1){
			$oponent = $findOponentResult->fetch_assoc();
			if($oponent['life'] > 0){
				$date = time();
				$addDuelPending = $conn->prepare("INSERT INTO duelground(`fromusername`, `tousername`, `status`, `turn`, `time`) VALUES (?, ?, 'Requesting', ?, ?)");
				$addDuelPending->bind_param("sssi", $char['username'], $oponent['username'], $char['username'], $date);
				$addDuelPending->execute();
				$messagechat = "<strong><font color=\'#FF3300\'>".$char['username']." has requested a duel against you! <a href=\'javascript: acceptFight();\'>Accept</a> | <a href=\'javascript: declineFight();\'>Decline</a></font></strong><br />";
				$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)");
				$query->bind_param("iss", $date, $messagechat, $oponent['username']);
				$query->execute();
			}else{
				print("alert('Opponent is already dead...');");
			}
		}else{
			print("alert('Failed to find opponent...');");
		}
	}
}
include('updatestats.php');
?>