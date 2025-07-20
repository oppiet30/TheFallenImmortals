<?php
session_name("icsession");
session_start();
include('db.php');
$getchar = mysqli_query($conn, "SELECT * FROM characters WHERE id='".$_SESSION['userid']."'");
$char = mysql_fetch_assoc($getchar);

if($_POST['charrequesting'] != NULL){
	$currentDuel = mysqli_query($conn, "SELECT * FROM duelground WHERE tousername='".$char['username']."' OR fromusername='".$char['username']."'");
	if(mysql_num_rows($currentDuel) >= 1){
		print("alert('You already have a pending duel request. Check the chatroom.');");
	}else{
		$findOponent = mysqli_query($conn, "SELECT * FROM characters WHERE username='".$_POST['charrequesting']."'")or die(mysql_error());
		if(mysql_num_rows($findOponent) == 1){
			$oponent = mysql_fetch_assoc($findOponent);
			if($oponent['life'] > 0){
				$date = time();
				$addDuelPending = mysqli_query($conn, "INSERT INTO duelground(`fromusername`, `tousername`, `status`, `turn`, `time`) VALUES ('".$char['username']."', '".$oponent['username']."', 'Requesting', '".$char['username']."', '".$date."')");
				$messagechat = "<strong><font color=\'#FF3300\'>".$char['username']." has requested a duel against you! <a href=\'javascript: acceptFight();\'>Accept</a> | <a href=\'javascript: declineFight();\'>Decline</a></font></strong><br />";
				$query = mysqli_query($conn, "INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES ('".$date."', '4', 'PM', '".$messagechat."', '".$oponent['username']."')");
			}else{
				print("alert('Oponent is already dead...');");
			}
		}else{
			print("alert('Failed to find oponent...');");
		}
	}
}
include('updatestats.php');
?>