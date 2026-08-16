<?php
session_name("fallenimmortals");
session_start();
include('db.php');
$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute();
$char = $getchar->get_result()->fetch_assoc();

$findDuel = $conn->prepare("SELECT * FROM duelground WHERE `tousername`=?");
$findDuel->bind_param("s", $char['username']);
$findDuel->execute();
$findDuelResult = $findDuel->get_result();
if($findDuelResult->num_rows == 0){
	print("alert('No duel to Accept.');");
}else{
	$date = time();
	$duel = $findDuelResult->fetch_assoc();
	$messagechat = "<strong><font color=\'#FF3300\'>Duel accepted.... ".$duel['fromusername']." starts the battle.</font></strong><br />";
	$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)");
	$query->bind_param("iss", $date, $messagechat, $duel['tousername']);
	$query->execute();
	if($char['posx'] != "25" || $char['posy'] != "25"){
		print("alert('You have been appointed to the Duel Ground.');");
		$updateCharacterLocation = $conn->prepare("UPDATE characters SET posx='25', posy='25' WHERE username=?");
		$updateCharacterLocation->bind_param("s", $char['username']);
		$updateCharacterLocation->execute();
	}

	$messagechat = "<strong><font color=\'#FF3300\'>".$char['username']." has accepted your duel! <a href=\'javascript: attackFight();\'>Attack</a>!</font></strong><br />";
	$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)");
	$query->bind_param("iss", $date, $messagechat, $duel['fromusername']);
	$query->execute();
	$startTheDuel = $conn->prepare("UPDATE duelground SET `status`='Started', `time`=? WHERE `id`=?");
	$startTheDuel->bind_param("ii", $date, $duel['id']);
	$startTheDuel->execute();
}
include('updatestats.php');
?>