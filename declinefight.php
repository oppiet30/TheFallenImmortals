<?php
session_name("icsession");
session_start();
include('db.php');
$getchar = mysqli_query($conn, "SELECT * FROM characters WHERE id='".$_SESSION['userid']."'");
$char = mysql_fetch_assoc($getchar);

$findDuel = mysqli_query($conn, "SELECT * FROM duelground WHERE `tousername`='".$char['username']."'");
if(mysql_num_rows($findDuel) == 0){
	print("alert('No duel to decline.');");
}else{
	$date = time();
	$duel = mysql_fetch_assoc($findDuel);
	$messagechat = "<strong><font color=\'#FF3300\'>Duel declined.</font></strong><br />";
	$query = mysqli_query($conn, "INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES ('".$date."', '4', 'PM', '".$messagechat."', '".$duel['tousername']."')");
	$messagechat = "<strong><font color=\'#FF3300\'>".$char['username']." has declined the duel.</font></strong><br />";
	$query = mysqli_query($conn, "INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES ('".$date."', '4', 'PM', '".$messagechat."', '".$duel['fromusername']."')");
	$removeTheDuel = mysqli_query($conn, "DELETE FROM duelground WHERE `id`='".$duel['id']."'");
}
include('updatestats.php');
?>