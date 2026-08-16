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
	print("alert('No duel to decline.');");
}else{
	$date = time();
	$duel = $findDuelResult->fetch_assoc();
	$messagechat = "<strong><font color=\'#FF3300\'>Duel declined.</font></strong><br />";
	$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)");
	$query->bind_param("iss", $date, $messagechat, $duel['tousername']);
	$query->execute();
	$messagechat = "<strong><font color=\'#FF3300\'>".$char['username']." has declined the duel.</font></strong><br />";
	$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)");
	$query->bind_param("iss", $date, $messagechat, $duel['fromusername']);
	$query->execute();
	$removeTheDuel = $conn->prepare("DELETE FROM duelground WHERE `id`=?");
	$removeTheDuel->bind_param("i", $duel['id']);
	$removeTheDuel->execute();
}
include('updatestats.php');
?>