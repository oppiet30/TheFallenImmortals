<?php

session_name("fallenimmortals");

session_start();

include('db.php');





$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");

$getchar->bind_param("i", $_SESSION['userid']);

$getchar->execute() or die($conn->error);

$char = $getchar->get_result()->fetch_assoc();

$char['chatlog'] = $char['chatlog'] ?? 0;

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
    $setstatus = $conn->prepare("UPDATE characters SET status='Normal', lastactive=?, endsuspend='0', reason='None' WHERE username=?");
    $setstatus->bind_param("is", $whatTime, $char['username']);
    $setstatus->execute();

	$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`)VALUES (?, '3', ?, ?, 'Chatroom')");
	$query->bind_param("iss", $date, $char['username'], $suspendmessage);
	$query->execute() or die($conn->error);
}

else

{



$findOfflineMessages = $conn->prepare("SELECT * FROM `chatroommessage` WHERE `to`=?");

$findOfflineMessages->bind_param("s", $char['username']);

$findOfflineMessages->execute();

$findOfflineMessagesResult = $findOfflineMessages->get_result();

if($findOfflineMessagesResult->num_rows > 1 && $activeTime < $charLastActive){

	$message = "<b><u>Messages while you were offline:</u></b><br />";

	$findMsg = $findOfflineMessagesResult->fetch_assoc();



	$message .= "".$findMsg['message']."";

	$activeTime = time();

	$query = $conn->prepare("INSERT INTO `chatroom` (`date`, `userlevel`, `username`, `to`, `message`) VALUES (?, '4', 'PM', ?, ?)");

	$query->bind_param("iss", $activeTime, $char['username'], $message);

	$query->execute();

	$deleteTehFreakingMessage = $conn->prepare("DELETE FROM `chatroommessage` WHERE `to`=?");

	$deleteTehFreakingMessage->bind_param("s", $char['username']);

	$deleteTehFreakingMessage->execute();

}



//---Checking for duel



$findYourDuel = $conn->prepare("SELECT * FROM duelground WHERE `fromusername`=?");

$findYourDuel->bind_param("s", $char['username']);

$findYourDuel->execute();

$duel = $findYourDuel->get_result()->fetch_assoc();

$date = time();

$timeofDuel = ($duel['time'] ?? 0) + "30";

if($timeofDuel < $date && $char['username'] != NULL && ($duel['fromusername'] ?? NULL) != NULL){

	$messagechat = "<strong><font color=\'#FF3300\'>".$duel['tousername']." has taken too much time to accept the duel. Try again later.</font></strong><br />";

	$duelTimeoutInsert = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)");

	$duelTimeoutInsert->bind_param("iss", $date, $messagechat, $duel['fromusername']);

	$duelTimeoutInsert->execute();



    $messagechat = "<strong><font color=\'#FF3300\'>You took to much time to accept the duel from ".$duel['fromusername'].".</font></strong><br />";

    $duelTimeoutInsert->bind_param("iss", $date, $messagechat, $duel['tousername']);

    $duelTimeoutInsert->execute();



    $deleteDuelRequest = $conn->prepare("DELETE FROM duelground WHERE `id`=?");

    $deleteDuelRequest->bind_param("i", $duel['id']);

    $deleteDuelRequest->execute();

}



	

    $data = "";

    if($char['userlevel'] == "1" && $char['userlevel'] != NULL && $char['userlevel'] != "")    //Admin

    {

        $getmessages = $conn->prepare("SELECT * FROM chatroom WHERE `to`=? OR `to`='Admin' OR `to`='Mod' OR `to`='Chatroom' OR `username`=? AND id>? ORDER BY id DESC LIMIT 40");

        $getmessages->bind_param("ssi", $char['username'], $char['username'], $char['chatlog']);

        $getmessages->execute();

        $getmessagesResult = $getmessages->get_result();

    }

    elseif($char['userlevel'] == "2" && $char['userlevel'] != NULL && $char['userlevel'] != "")    //Mod

    {

        $getmessages = $conn->prepare("SELECT * FROM chatroom WHERE `to`=? OR `to`='Mod' OR `to`='Chatroom' OR `username`=? AND id>? ORDER BY id DESC LIMIT 40");

        $getmessages->bind_param("ssi", $char['username'], $char['username'], $char['chatlog']);

        $getmessages->execute();

        $getmessagesResult = $getmessages->get_result();

    }

    else    //Player

    {

        $getmessages = $conn->prepare("SELECT * FROM chatroom WHERE `to`=? OR `to`='Chatroom' OR `username`=? AND id>? ORDER BY id DESC LIMIT 20");

        $getmessages->bind_param("ssi", $char['username'], $char['username'], $char['chatlog']);

        $getmessages->execute();

        $getmessagesResult = $getmessages->get_result();

    }



    while($messages = $getmessagesResult->fetch_array())

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

    $findonline = $conn->prepare("SELECT * FROM characters WHERE lastactive>? ORDER BY userlevel, id");

    $findonline->bind_param("i", $time);

    $findonline->execute();

    $findonlineResult = $findonline->get_result();

    $numonline = $findonlineResult->num_rows;

    $data2 = "";

    $findGuildTag = $conn->prepare("SELECT * FROM guilds WHERE name=?");

    while($active = $findonlineResult->fetch_assoc())

    {

        $onlineplayer = $active['username'];

		$findGuildTag->bind_param("s", $active['guild']);

		$findGuildTag->execute();

		$guildTag = $findGuildTag->get_result()->fetch_assoc();

        $onlineplayer = str_replace('"', "'", $onlineplayer);

        $onlineplayer = str_replace("<", "&lt;", $onlineplayer);

        $onlineplayer = str_replace(">", "&gt;", $onlineplayer);



        $colour = $active['chatcolour'];


		if(($guildTag['tag'] ?? "") == ""){
			$tag = "";
		}else{
			$tag = "(".$guildTag['tag'].")";
		}


        if(($active['access'] ?? "") == "Admin" && $data != "")

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