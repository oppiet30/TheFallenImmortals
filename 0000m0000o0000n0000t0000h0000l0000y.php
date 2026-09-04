<?php
include('db-conn.php');
$date = time();

$ticketQuery = db_query("SELECT * FROM donationpot");
$ticketRow = db_num_rows($ticketQuery);

if($ticketRow == 0) die();

$getWinner = db_query("SELECT * FROM donationpot ORDER BY RAND() LIMIT 1");
$winner = db_fetch_array($getWinner);

$gettemple = db_query("SELECT * FROM temple");
$temple = db_fetch_assoc($gettemple);

$updateUser = db_query("UPDATE characters SET gold=gold+? WHERE username=?", [$temple['pot'], $winner['username']]);

$messagechat = "<strong><font color=\'orange\'>".$winner['username']." sucsessfully robbed the temple for ".number_format($temple['pot'])." gold!</font></strong><br />";
$query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `message`, `to`) VALUES (?, '3', ?, 'Chatroom')", [$date, $messagechat]);

$updateTemple = db_query("UPDATE temple SET pot='0', lastwinner=?", [$winner['username']]);

$deleteTickets = db_query("TRUNCATE TABLE  `donationpot`");
?>