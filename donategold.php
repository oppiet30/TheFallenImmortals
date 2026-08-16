<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include('varset.php');
$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute() or die("Not logged in!");
$char = $getchar->get_result()->fetch_assoc();

$amount = $_POST['amount'];
if(isset($_POST['amount']) && $amount > "0" && $chargold > "0")
{
    $getguild = $conn->prepare("SELECT * FROM guilds WHERE name=?");
    $getguild->bind_param("s", $char['guild']);
    $getguild->execute();
    $guild = $getguild->get_result()->fetch_assoc();
    if($amount >= $chargold  && $amount > "0" && $chargold > "0")
    {
        /*
        This will set the designated donation to the amount of gold the player has on hand if
        they have entered more than they posess
        */
        $amount = $chargold;
    }
    $newgold = floor($chargold - $amount);
    $newbank = floor($guild['bank'] + $amount);
    $newtd = $char['totaldonations'] + $amount;
    if($newbank > "1000000000000000000")
    {
        /*
        This code will run if the player is trying to put the cap of 1Quint into the Guild Bank
        This code will find the excess gold and automatically reimburse the player the difference
        */
        $reimburse = $newbank - "1000000000000000000";
        $newgold += $reimburse;
        $newbank = "1000000000000000000";
    }
    $message = "donated ".number_format($amount)." gold to the guild! ".$char['username']." has contributed ".number_format($newtd)." gold to the guild in total.";
    $message = "<font color=\'#DD00DD\'><strong>Guild:</strong></font> (<a href=\'javascript:toptell(\"".$char['username']."\");\'><font color=\'#DD00DD\' style=\'text-decoration:none\'>".$charname."</font></a>)<font color=\'#DD00DD\'> ".$message."</font><br />";
    $getmembers = $conn->prepare("SELECT * FROM characters WHERE guild=?");
    $getmembers->bind_param("s", $charguild);
    $getmembers->execute();
    $getmembersResult = $getmembers->get_result();
    $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `to`, `message`) VALUES (?, '4', 'PM', ?, ?)");
    while($member = $getmembersResult->fetch_array())
    {
        $query->bind_param("iss", $timestamp, $member['username'], $message);
        $query->execute();
    }
    $logMessage = "".$char['username']." donated ".number_format($amount)." gold to the guild!";
    $letGuildKnow = $conn->prepare("INSERT INTO log (`name`, `message`) VALUES (?, ?)");
    $letGuildKnow->bind_param("ss", $charguild, $logMessage);
    $letGuildKnow->execute();
    $removegold = $conn->prepare("UPDATE characters SET gold=?, totaldonations=? WHERE id=?");
    $removegold->bind_param("iii", $newgold, $newtd, $_SESSION['userid']);
    $removegold->execute();
    $donategold = $conn->prepare("UPDATE guilds SET bank=? WHERE name=?");
    $donategold->bind_param("is", $newbank, $char['guild']);
    $donategold->execute();
    include('updatestats.php');
    print("viewGuild();");
}else{
	print('alert(\'You cannot donate you negative money!\');');
}
?>