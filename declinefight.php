<?php
session_name("icsession");
session_start();
include('db.php');
$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);

$findDuel = db_query("SELECT * FROM duelground WHERE `tousername`=?", [$char['username']]);
if(db_num_rows($findDuel) == 0){
	print("alert('No duel to decline.');");
}else{
	$date = time();
	$duel = db_fetch_assoc($findDuel);
	$messagechat = "<strong><font color=\'#FF3300\'>Duel declined.</font></strong><br />";
	$query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)", [$date, $messagechat, $duel['tousername']]);
	$messagechat = "<strong><font color=\'#FF3300\'>".$char['username']." has declined the duel.</font></strong><br />";
	$query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)", [$date, $messagechat, $duel['fromusername']]);
	$removeTheDuel = db_query("DELETE FROM duelground WHERE `id`=?", [$duel['id']]);
}
include('updatestats.php');
?>