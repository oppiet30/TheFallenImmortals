<?php
error_reporting(E_ALL ^ E_DEPRECATED);
include('db-conn.php');
include('varset.php');

//Global Variables
$date = time();

//Auto Administration
//Banned
$getbanned = $conn->prepare("SELECT * FROM banned WHERE ip=?");
$getbanned->bind_param("s", $charip);
$getbanned->execute();
if($getbanned->get_result()->num_rows == "1")
{
    print("alert('You are banned.');");
    print("window.location = 'http://fallenimmortals.old/';");
}

//Unmute
$getmuted = mysqli_query($conn, "SELECT * FROM muted");
while($muted = mysqli_fetch_array($getmuted))
{
    if($muted['mutetime'] <= time())
    {
        $unmute = $conn->prepare("DELETE FROM muted WHERE id=?");
        $unmute->bind_param("i", $muted['id']);
        $unmute->execute();

        $unmutemessage = "<b><font color=\'#DD00DD\'>Player ".$muted['username']." has been unmuted!</font></b><br />";
        $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`)
        VALUES (?, '3', ?, ?, 'Chatroom')");
        $query->bind_param("iss", $date, $muted['mutedby'], $unmutemessage);
        $query->execute() or die($conn->error);
    }
}
?>