<?php
session_name("fallenimmortals");
session_start();
include('db.php');
$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);

$findDuel = db_query("SELECT * FROM duelground WHERE `tousername`=?", [$char['username']]);
if(db_num_rows($findDuel) == 0){
	print("alert('No duel to Accept.');");
}else{
	$date = time();
	$duel = db_fetch_assoc($findDuel);
	$messagechat = "<strong><font color=\'#FF3300\'>Duel accepted.... ".$duel['fromusername']." starts the battle.</font></strong><br />";
	$query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)", [$date, $messagechat, $duel['tousername']]);
	if($char['posx'] != "25" || $char['posy'] != "25"){
		print("alert('You have been appointed to the Duel Ground.');");
		$updateCharacterLocation = db_query("UPDATE characters SET posx='25', posy='25' WHERE username=?", [$char['username']]);
	}
	
	$messagechat = "<strong><font color=\'#FF3300\'>".$char['username']." has accepted your duel! <a href=\'javascript: attackFight();\'>Attack</a>!</font></strong><br />";
	$query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)", [$date, $messagechat, $duel['fromusername']]);
	$startTheDuel = db_query("UPDATE duelground SET `status`='Started', `time`=? WHERE `id`=?", [$date, $duel['id']]);
}
include('updatestats.php');
?>