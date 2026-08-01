<?php
error_reporting(E_ALL ^ E_DEPRECATED);
include('db-conn.php');
include('varset.php');

//Global Variables
$date = time();

//Auto Administration
//Banned
$getbanned = mysqli_query($conn, "SELECT * FROM banned WHERE ip='".$charip."'");
if(mysqli_num_rows($getbanned) == "1")
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
        $unmute = mysqli_query($conn, "DELETE FROM muted WHERE id='".$muted['id']."'");

        $unmutemessage = "<b><font color=\'#DD00DD\'>Player ".$muted['username']." has been unmuted!</font></b><br />";
        $query = mysqli_query($conn, "INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`)
        VALUES ('".$date."', '3', '".$muted['mutedby']."', '".$unmutemessage."', 'Chatroom')") or die(mysqli_error($conn));
    }
}
?>