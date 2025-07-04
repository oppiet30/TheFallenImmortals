<?php
session_name("icsession");
session_start();
include('db.php');
$getchar = mysqli_query("SELECT * FROM characters WHERE id='".$_SESSION['userid']."'");
$char = mysqli_fetch_assoc($getchar);

$findDuel = mysqli_query("SELECT * FROM duelground WHERE `tousername`='".$char['username']."'");
if(mysqli_num_rows($findDuel) == 0){
	print("alert('No duel to Accept.');");
}else{
	$date = time();
	$duel = mysqli_fetch_assoc($findDuel);
	$messagechat = "<strong><font color=\'#FF3300\'>Duel accepted.... ".$duel['fromusername']." starts the battle.</font></strong><br />";
	$query = mysqli_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES ('".$date."', '4', 'PM', '".$messagechat."', '".$duel['tousername']."')");
	if($char['posx'] != "25" || $char['posy'] != "25"){
		print("alert('You have been appointed to the Duel Ground.');");
		$updateCharacterLocation = mysqli_query("UPDATE characters SET posx='25', posy='25' WHERE username='".$char['username']."'");
	}
	
	$messagechat = "<strong><font color=\'#FF3300\'>".$char['username']." has accepted your duel! <a href=\'javascript: attackFight();\'>Attack</a>!</font></strong><br />";
	$query = mysqli_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES ('".$date."', '4', 'PM', '".$messagechat."', '".$duel['fromusername']."')");
	$startTheDuel = mysqli_query("UPDATE duelground SET `status`='Started', `time`='".$date."' WHERE `id`='".$duel['id']."'");
}
include('updatestats.php');
?>