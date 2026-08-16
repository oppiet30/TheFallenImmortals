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

    $updateUser = $conn->prepare("UPDATE characters SET gold=gold+? WHERE username=?");
    $updateUser->bind_param("is", $temple['pot'], $winner['username']);
    $updateUser->execute();

    $messagechat = "<strong><font color=\'orange\'>".$winner['username']." successfully robbed the temple for ".number_format($temple['pot'])." gold!</font></strong><br />";
    $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `message`, `to`) VALUES (?, '3', ?, 'Chatroom')");
    $query->bind_param("is", $date, $messagechat);
    $query->execute();

    $updateTemple = $conn->prepare("UPDATE temple SET pot='0', lastwinner=?");
    $updateTemple->bind_param("s", $winner['username']);
    $updateTemple->execute();

    $deleteTickets = mysqli_query($conn, "TRUNCATE TABLE  `donationpot`");
}
?>