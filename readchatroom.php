<?php

session_name("icsession");

session_start();

include('db.php');





$getchar = mysqli_query($conn, "SELECT * FROM characters WHERE id='".$_SESSION['userid']."'") or die(mysqli_error());

$char = mysqli_fetch_assoc($getchar);



$activeTime = time();

$charLastActive = $char['lastactive'] + 900;



if($char['id'] == NULL || $activeTime > $charLastActive)

{

    print("window.location = 'http://fallenimmortals.old/';");

}

if($char['status'] == "Suspended" && $char['endsuspend'] > $activeTime)

{

    session_destroy();

    print("alert('This account is suspended.');");

    print("window.location = 'http://fallenimmortals.old/';");
	die();

}

if($char['endsuspend'] < $activeTime && $char['endsuspend'] > "0"){
	$suspendmessage = "<b><font color=\'#DD00DD\'>Player ".$char['username']." has been unsuspended.</font></b><br />";

    $date = date('ymdHi');
	$whatTime = time();
    $setstatus = mysqli_query($conn, "UPDATE characters SET status='Normal', lastactive='".$whatTime."', endsuspend='0', reason='None' WHERE username='".$char['username']."'");
	
	$query = mysqli_query($conn, "INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`)VALUES ('".$date."', '3', '".$char['username']."', '".$suspendmessage."', 'Chatroom')") or die(mysqli_error());
}

else

{



$finder = "SELECT * FROM `chatroommessage` WHERE `to`='".$char['username']."'";

$findOfflineMessages = mysqli_query($conn, $finder);

if(mysqli_num_rows($findOfflineMessages) > 1 && $activeTime < $charLastActive){

	$message = "<b><u>Messages while you were offline:</u></b><br />";

	$findMsg = mysqli_fetch_assoc($findOfflineMessages);

		

	$message .= "".mysqli_real_escape_string($conn, $findMsg['message'])."";

	$activeTime = time();

	$query = mysqli_query($conn, "INSERT INTO `chatroom` (`date`, `userlevel`, `username`, `to`, `message`) VALUES ('".$activeTime."', '4', 'PM', '".$char['username']."', '".mysqli_real_escape_string($conn, $message)."')");

	$deleteTehFreakingMessage = mysqli_query($conn, "DELETE FROM `chatroommessage` WHERE `to`='".$char['username']."'");

}



//---Checking for duel



$findYourDuel = mysqli_query($conn, "SELECT * FROM duelground WHERE `fromusername`='".$char['username']."'");

$duel = mysqli_fetch_assoc($findYourDuel);

$date = time();

$timeofDuel = $duel['time'] + "30";

if($timeofDuel < $date && $char['username'] != NULL && $duel['fromusername'] != NULL){

	$messagechat = "<strong><font color=\'#FF3300\'>".$duel['tousername']." has taken too much time to accept the duel. Try again later.</font></strong><br />";

	$messagechat = addslashes($messagechat);

    $query = mysqli_query($conn, "INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES ('".$date."', '4', 'PM', '".$messagechat."', '".$duel['fromusername']."')");

    

    $messagechat = "<strong><font color=\'#FF3300\'>You took to much time to accept the duel from ".$duel['fromusername'].".</font></strong><br />";

    $messagechat = addslashes($messagechat);

    $query = mysqli_query($conn, "INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES ('".$date."', '4', 'PM', '".$messagechat."', '".$duel['tousername']."')");

    

    $deleteDuelRequest = mysqli_query($conn, "DELETE FROM duelground WHERE `id`='".$duel['id']."'");

}



	

    $data = "";

    if($char['userlevel'] == "1" && $char['userlevel'] != NULL && $char['userlevel'] != "")    //Admin

    {

        $getmessages = mysqli_query($conn, "SELECT * FROM chatroom WHERE `to`='".$char['username']."' OR `to`='Admin' OR `to`='Mod' OR `to`='Chatroom' OR `username`='".$char['username']."' AND id>'".$char['chatlog']."' ORDER BY id DESC LIMIT 40");

    }

    elseif($char['userlevel'] == "2" && $char['userlevel'] != NULL && $char['userlevel'] != "")    //Mod

    {

        $getmessages = mysqli_query($conn, "SELECT * FROM chatroom WHERE `to`='".$char['username']."' OR `to`='Mod' OR `to`='Chatroom' OR `username`='".$char['username']."' AND id>'".$char['chatlog']."' ORDER BY id DESC LIMIT 40");

    }

    else    //Player

    {

        $getmessages = mysqli_query($conn, "SELECT * FROM chatroom WHERE `to`='".$char['username']."' OR `to`='Chatroom' OR `username`='".$char['username']."' AND id>'".$char['chatlog']."' ORDER BY id DESC LIMIT 20");

    }



    while($messages = mysqli_fetch_array($getmessages))

    {

        $username = $messages['username'];

        $userlevel = $messages['userlevel'];

        $to = $messages['to'];

        $date = $messages['date'];

        $message = str_replace("+", "\+", $messages['message']);

        $message = str_replace("&", "\&", $messages['message']);

        $message = str_replace("'", "\'", $message);

        $message = str_replace("\\\\", "\\", $message);

    

        $data = $data.$message;

    }

    print("fillDiv('chatRoom','".$data."');");



    //Online List

    $time = time() - "600";

    $findonline = mysqli_query($conn, "SELECT * FROM characters WHERE lastactive>'".$time."' ORDER BY userlevel, id");

    $numonline = mysqli_num_rows($findonline);



    while($active = mysqli_fetch_assoc($findonline))

    {

        $onlineplayer = $active['username'];
		
		$findGuildTag = mysqli_query($conn, "SELECT * FROM guilds WHERE name='".$active['guild']."'");
		
		$guildTag = mysqli_fetch_assoc($findGuildTag);

        $onlineplayer = str_replace('"', "'", $onlineplayer);

        $onlineplayer = str_replace("<", "&lt;", $onlineplayer);

        $onlineplayer = str_replace(">", "&gt;", $onlineplayer);



        $colour = $active['chatcolour'];


		if($guildTag['tag'] == ""){
			$tag = "";
		}else{
			$tag = "(".$guildTag['tag'].")";
		}


        if($active['access'] == "Admin" && $data != "")

        {

            $data2 .= "<tr><td><a href=\'javascript:toptell(\"$onlineplayer\");\'><strong><font color=\'#".$colour."\'>".$onlineplayer."".$tag."</font></strong></font></a></td><td>".number_format($active['level'])."</td></tr>";

        }

        elseif($data != "")

        {

            $data2 .= "<tr><td><a href=\'javascript:toptell(\"$onlineplayer\");\'><font color=\'#".$colour."\'>".$onlineplayer."".$tag."</font></a></td><td>".number_format($active['level'])."</td></tr>";

        }

    }

    $data2 = "<center><b>Online List (".$numonline.")</b><table border=\'0\' width=\'75%\'><tr><td align=\'center\'><strong>Username</strong></td><td align=\'center\'><strong>Level</strong></td></tr>".$data2."</table></center>";

    print("fillDiv('onlineList','".$data2."');");

}











?>