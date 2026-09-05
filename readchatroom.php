<?php

session_name("fallenimmortals");

session_start();

include('db.php');





$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);

$char = db_fetch_assoc($getchar);



$activeTime = time();

$charLastActive = $char['lastactive'] + 900;



if($char['id'] == NULL || $activeTime > $charLastActive)

{

    print("window.location = '".db_base_url()."/';");

}

if($char['status'] == "Suspended" && $char['endsuspend'] > $activeTime)

{

    session_destroy();

    print("alert('This account is suspended.');");

    print("window.location = '".db_base_url()."/';");
	die();

}

if($char['endsuspend'] < $activeTime && $char['endsuspend'] > "0"){
	$suspendmessage = "<b><font color=\'#DD00DD\'>Player ".$char['username']." has been unsuspended.</font></b><br />";

    $date = date('ymdHi');
	$whatTime = time();
    $setstatus = db_query("UPDATE characters SET status='Normal', lastactive=?, endsuspend='0', reason='None' WHERE username=?", [$whatTime, $char['username']]);
	
	$query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`)VALUES (?, '3', ?, ?, 'Chatroom')", [$date, $char['username'], $suspendmessage]);
}

else

{



$findOfflineMessages = db_query("SELECT * FROM `chatroommessage` WHERE `to`=?", [$char['username']]);

if(db_num_rows($findOfflineMessages) > 1 && $activeTime < $charLastActive){

	$message = "<b><u>Messages while you were offline:</u></b><br />";

	$findMsg = db_fetch_assoc($findOfflineMessages);

		

	$message .= "".db_escape($findMsg['message'])."";

	$activeTime = time();

	$query = db_query("INSERT INTO `chatroom` (`date`, `userlevel`, `username`, `to`, `message`) VALUES (?, '4', 'PM', ?, ?)", [$activeTime, $char['username'], $message]);

	$deleteTehFreakingMessage = db_query("DELETE FROM `chatroommessage` WHERE `to`=?", [$char['username']]);

}



//---Checking for duel



$findYourDuel = db_query("SELECT * FROM duelground WHERE `fromusername`=? AND `status`='Requesting'", [$char['username']]);

$duel = db_fetch_assoc($findYourDuel);

$date = time();

if($duel !== false){

$timeofDuel = $duel['time'] + "30";

if($timeofDuel < $date && $char['username'] != NULL && $duel['fromusername'] != NULL){

	$messagechat = "<strong><font color=\'#FF3300\'>".$duel['tousername']." has taken too much time to accept the duel. Try again later.</font></strong><br />";

    $query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)", [$date, $messagechat, $duel['fromusername']]);

    

    $messagechat = "<strong><font color=\'#FF3300\'>You took to much time to accept the duel from ".$duel['fromusername'].".</font></strong><br />";

    $query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)", [$date, $messagechat, $duel['tousername']]);

    

    $deleteDuelRequest = db_query("DELETE FROM duelground WHERE `id`=?", [$duel['id']]);

}

}



	

    $data = "";

    if($char['userlevel'] == "1" && $char['userlevel'] != NULL && $char['userlevel'] != "")    //Admin

    {

        $getmessages = db_query("SELECT * FROM chatroom WHERE `to`=? OR `to`='Admin' OR `to`='Mod' OR `to`='Chatroom' OR `username`=? AND id>? ORDER BY id DESC LIMIT 40", [$char['username'], $char['username'], $char['chatlog'] ?? 0]);

    }

    elseif($char['userlevel'] == "2" && $char['userlevel'] != NULL && $char['userlevel'] != "")    //Mod

    {

        $getmessages = db_query("SELECT * FROM chatroom WHERE `to`=? OR `to`='Mod' OR `to`='Chatroom' OR `username`=? AND id>? ORDER BY id DESC LIMIT 40", [$char['username'], $char['username'], $char['chatlog'] ?? 0]);

    }

    else    //Player

    {

        $getmessages = db_query("SELECT * FROM chatroom WHERE `to`=? OR `to`='Chatroom' OR `username`=? AND id>? ORDER BY id DESC LIMIT 20", [$char['username'], $char['username'], $char['chatlog'] ?? 0]);

    }



    while($messages = db_fetch_array($getmessages))

    {

        $username = $messages['username'];

        $userlevel = $messages['userlevel'];

        $to = $messages['to'];

        $date = $messages['date'];

        $message = $messages['message'];

        $message = str_replace("\\'", "'", $message);

        $message = str_replace("\\+", "+", $message);

        $message = str_replace("\\&", "&", $message);

        $data = $data.$message;

    }

    print("fillDiv('chatRoom',".json_encode($data, JSON_HEX_APOS).");");



    //Online List

    $time = time() - "600";

    $findonline = db_query("SELECT * FROM characters WHERE lastactive>? ORDER BY userlevel, id", [$time]);

    $numonline = db_num_rows($findonline);

    $data2 = "";



    while($active = db_fetch_assoc($findonline))

    {

        $onlineplayer = $active['username'];
		
		$findGuildTag = db_query("SELECT * FROM guilds WHERE name=?", [$active['guild']]);
		
		$guildTag = db_fetch_assoc($findGuildTag);

        $onlineplayer = str_replace('"', "'", $onlineplayer);

        $onlineplayer = str_replace("<", "&lt;", $onlineplayer);

        $onlineplayer = str_replace(">", "&gt;", $onlineplayer);



        $colour = $active['chatcolour'];


		if($guildTag !== false && $guildTag['tag'] != ""){
			$tag = "(".$guildTag['tag'].")";
		}else{
			$tag = "";
		}


        if(($active['access'] ?? '') == "Admin" && $data != "")

        {

            $data2 .= "<tr><td><a href=\'javascript:toptell(\"$onlineplayer\");\'><strong><font color=\'#".$colour."\'>".$onlineplayer."".$tag."</font></strong></font></a></td><td>".number_format($active['level'])."</td></tr>";

        }

        elseif($data != "")

        {

            $data2 .= "<tr><td><a href=\'javascript:toptell(\"$onlineplayer\");\'><font color=\'#".$colour."\'>".$onlineplayer."".$tag."</font></a></td><td>".number_format($active['level'])."</td></tr>";

        }

    }

    $data2 = "<center><b>Online List (".$numonline.")</b><table border=\'0\' width=\'75%\'><tr><td align=\'center\'><strong>Username</strong></td><td align=\'center\'><strong>Level</strong></td></tr>".$data2."</table></center>";

    print("fillDiv('onlineList',".json_encode($data2, JSON_HEX_APOS).");");

}











?>