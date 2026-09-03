<?php

session_name("icsession");

session_start();

include('db.php');

$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);

$char = db_fetch_assoc($getchar);

$display = "";



if($_POST['newUsername'] != NULL || $_POST['newUsername'] != "" && $char['changeusername'] == "1"){

	$username = $_POST['newUsername'];

	$currentusername = $char['username'];

	if(preg_match("/^[a-z0-9_]+$/i", $username))

	{

	    $getuser = db_query("SELECT * FROM characters WHERE username=?", [$username]);

	    if(db_num_rows($getuser) != "1")    //Username does not exist

	    {

	        $display .= "Username: <font color=\'#00DD00\'>OK</font><br />";

	        $guildLeaderCheck = db_query("SELECT * FROM guilds WHERE leader=?", [$char['username']]);

	        if(db_num_rows($guildLeaderCheck) == "1"){

	        	$updateGuildLeader = db_query("UPDATE guilds SET leader=? WHERE leader=?", [$username, $char['username']]);

	        }

	        $guildColeaderCheck = db_query("SELECT * FROM guilds WHERE coleader=?", [$char['username']]);

	        if(db_num_rows($guildColeaderCheck) == "1"){

	        	$updateGuildColeader = db_query("UPDATE guilds SET coleader=? WHERE coleader=?", [$username, $char['username']]);

	        }

	        $guildCaptianCheck = db_query("SELECT * FROM guilds WHERE captian=?", [$char['username']]);

	        if(db_num_rows($guildCaptianCheck) == "1"){

	        	$updateGuildCaptian = db_query("UPDATE guilds SET captian=? WHERE captian=?", [$username, $char['username']]);

	        }

	        $updateInventory = db_query("UPDATE inventory SET username=? WHERE username=?", [$username, $char['username']]);

	        $updateTrade = db_query("UPDATE trade SET fromplayer=? WHERE fromplayer=?", [$username, $char['username']]);

	        $updateUser = db_query("UPDATE characters SET changeusername='0', username=? WHERE id=?", [$username, $_SESSION['userid']]);

	        $updateScavenger = db_query("UPDATE scavenger SET username=? WHERE username=?", [$username, $currentusername]);
			
			$updateSecondClass = db_query("UPDATE secondclass SET username=? WHERE username=?", [$username, $currentusername]);

	        $date = time();

	        $cashmessage = "<b><font color=\'#00DD00\'>".$currentusername." just changed their username to ".$username."!</font></b><br />";

        	$query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`)VALUES(?, '3', ?, ?, 'Chatroom')", [$date, $currentusername, $cashmessage]);

	    }

	    else

	    {

	        $display .= "Username: <font color=\'#DD0000\'>Already Taken</font><br />";

	    }

	}else{

		$display .= "Username: <font color=\'#DD0000\'>Illegal Characters</font><br />";

	}

}else{

	$display .= "Failed even trying to make a change to your username.";

}

print("fillDiv('displayArea','".$display."');");

?>