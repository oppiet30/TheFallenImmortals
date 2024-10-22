<?php
session_name("icsession");
session_start();
include('db.php');

$ticketQuery = mysqli_query("SELECT * FROM donationpot");
$ticketRow = mysqli_num_rows($ticketQuery);

$choosenOne = rand(1,$ticketRow);

$getWinner = mysqli_query("SELECT * FROM donationpot WHERE id='".$choosenOne."'");
$winner = mysqli_fetch_array($getWinner);

$gettemple = mysqli_query("SELECT * FROM temple");
$temple = mysqli_fetch_assoc($gettemple);

$updateUser = mysqli_query("UPDATE characters SET gold=gold+'".$temple['pot']."' WHERE username='".$winner['username']."'");

$messagechat = "<strong><font color=\'orange\'>".$winner['username']." sucsessfully robbed the temple for ".number_format($temple['pot'])." gold!</font></strong><br />";
$query = mysqli_query("INSERT INTO chatroom (`date`, `userlevel`, `message`, `to`) VALUES ('".$date."', '3', '".$messagechat."', 'Chatroom')");

$updateTemple = mysqli_query("UPDATE temple SET pot='0', lastwinner='".$winner['username']."'");

$deleteTickets = mysqli_query("TRUNCATE TABLE  `donationpot`");
?>
