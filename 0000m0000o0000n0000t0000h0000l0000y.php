<?php
include('db-conn.php');
$date = time();

$ticketQuery = mysqli_query($conn, "SELECT * FROM donationpot");
$ticketRow = mysqli_num_rows($ticketQuery);

if($ticketRow > 0)
{
    $getWinner = mysqli_query($conn, "SELECT * FROM donationpot ORDER BY RAND() LIMIT 1");
    $winner = mysqli_fetch_assoc($getWinner);

    $gettemple = mysqli_query($conn, "SELECT * FROM temple");
    $temple = mysqli_fetch_assoc($gettemple);

    $updateUser = mysqli_query($conn, "UPDATE characters SET gold=gold+'".$temple['pot']."' WHERE username='".$winner['username']."'");

    $messagechat = "<strong><font color=\'orange\'>".$winner['username']." sucsessfully robbed the temple for ".number_format($temple['pot'])." gold!</font></strong><br />";
    $query = mysqli_query($conn, "INSERT INTO chatroom (`date`, `userlevel`, `message`, `to`) VALUES ('".$date."', '3', '".$messagechat."', 'Chatroom')");

    $updateTemple = mysqli_query($conn, "UPDATE temple SET pot='0', lastwinner='".$winner['username']."'");

    $deleteTickets = mysqli_query($conn, "TRUNCATE TABLE  `donationpot`");
}
?>