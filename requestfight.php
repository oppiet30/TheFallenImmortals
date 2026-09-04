<?php
session_name("fallenimmortals");
session_start();
include('db.php');
$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);

if($_POST['charrequesting'] != NULL){
	$currentDuel = db_query("SELECT * FROM duelground WHERE tousername=? OR fromusername=?", [$char['username'], $char['username']]);
	if(db_num_rows($currentDuel) >= 1){
		print("alert('You already have a pending duel request. Check the chatroom.');");
	}else{
		$findOponent = db_query("SELECT * FROM characters WHERE username=?", [$_POST['charrequesting']]);
		if(db_num_rows($findOponent) == 1){
			$oponent = db_fetch_assoc($findOponent);
			if($oponent['life'] > 0){
				$date = time();
				$addDuelPending = db_query("INSERT INTO duelground(`fromusername`, `tousername`, `status`, `turn`, `time`) VALUES (?, ?, 'Requesting', ?, ?)", [$char['username'], $oponent['username'], $char['username'], $date]);
				$messagechat = "<strong><font color=\'#FF3300\'>".$char['username']." has requested a duel against you! <a href=\'javascript: acceptFight();\'>Accept</a> | <a href=\'javascript: declineFight();\'>Decline</a></font></strong><br />";
				$query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)", [$date, $messagechat, $oponent['username']]);
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