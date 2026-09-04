<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include('varset.php');
$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);

$amount = $_POST['amount'];
if(isset($_POST['amount']) && $amount > "0" && $chargold > "0")
{
    $getguild = db_query("SELECT * FROM guilds WHERE name=?", [$char['guild']]);
    $guild = db_fetch_assoc($getguild);
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
    $getmembers = db_query("SELECT * FROM characters WHERE guild=?", [$charguild]);
    while($member = db_fetch_array($getmembers))
    {
        $query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `to`, `message`) VALUES (?, '4', 'PM', ?, ?)", [$timestamp, $member['username'], $message]);
    }
    $logMessage = "".$char['username']." donated ".number_format($amount)." gold to the guild!";
    $letGuildKnow = db_query("INSERT INTO log (`name`, `message`) VALUES (?, ?)", [$charguild, $logMessage]);
    $removegold = db_query("UPDATE characters SET gold=?, totaldonations=? WHERE id=?", [$newgold, $newtd, $_SESSION['userid']]);
    $donategold = db_query("UPDATE guilds SET bank=? WHERE name=?", [$newbank, $char['guild']]);
    include('updatestats.php');
    print("viewGuild();");
}else{
	print('alert(\'You cannot donate you negative money!\');');
}
?>