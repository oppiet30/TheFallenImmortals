<?php

session_name("fallenimmortals");

session_start();

include('db.php');

$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");

$getchar->bind_param("i", $_SESSION['userid']);

$getchar->execute() or die($conn->error);

$char = $getchar->get_result()->fetch_assoc();

$display = "";



if($_POST['newUsername'] != NULL || $_POST['newUsername'] != "" && $char['changeusername'] == "1"){

	$username = $_POST['newUsername'];

	$currentusername = $char['username'];

	if(preg_match("/^[a-z0-9_]+$/i", $username))

	{

	    $getuser = $conn->prepare("SELECT * FROM characters WHERE username=?");

	    $getuser->bind_param("s", $username);

	    $getuser->execute();

	    $getuserResult = $getuser->get_result();

	    if($getuserResult->num_rows != "1")    //Username does not exist

	    {

	        $display .= "Username: <font color=\'#00DD00\'>OK</font><br />";

	        $guildLeaderCheck = $conn->prepare("SELECT * FROM guilds WHERE leader=?");

	        $guildLeaderCheck->bind_param("s", $char['username']);

	        $guildLeaderCheck->execute();

	        if($guildLeaderCheck->get_result()->num_rows == "1"){

	        	$updateGuildLeader = $conn->prepare("UPDATE guilds SET leader=? WHERE leader=?");

	        	$updateGuildLeader->bind_param("ss", $username, $char['username']);

	        	$updateGuildLeader->execute();

	        }

	        $guildColeaderCheck = $conn->prepare("SELECT * FROM guilds WHERE coleader=?");

	        $guildColeaderCheck->bind_param("s", $char['username']);

	        $guildColeaderCheck->execute();

	        if($guildColeaderCheck->get_result()->num_rows == "1"){

	        	$updateGuildColeader = $conn->prepare("UPDATE guilds SET coleader=? WHERE coleader=?");

	        	$updateGuildColeader->bind_param("ss", $username, $char['username']);

	        	$updateGuildColeader->execute();

	        }

	        $guildCaptianCheck = $conn->prepare("SELECT * FROM guilds WHERE captain=?");

	        $guildCaptianCheck->bind_param("s", $char['username']);

	        $guildCaptianCheck->execute();

	        if($guildCaptianCheck->get_result()->num_rows == "1"){

	        	$updateGuildCaptian = $conn->prepare("UPDATE guilds SET captain=? WHERE captain=?");

	        	$updateGuildCaptian->bind_param("ss", $username, $char['username']);

	        	$updateGuildCaptian->execute();

	        }

	        $updateInventory = $conn->prepare("UPDATE inventory SET username=? WHERE username=?");

	        $updateInventory->bind_param("ss", $username, $char['username']);

	        $updateInventory->execute();

	        $updateTrade = $conn->prepare("UPDATE trade SET fromplayer=? WHERE fromplayer=?");

	        $updateTrade->bind_param("ss", $username, $char['username']);

	        $updateTrade->execute();

	        $updateUser = $conn->prepare("UPDATE characters SET changeusername='0', username=? WHERE id=?");

	        $updateUser->bind_param("si", $username, $_SESSION['userid']);

	        $updateUser->execute();

	        $updateScavenger = $conn->prepare("UPDATE scavenger SET username=? WHERE username=?");

	        $updateScavenger->bind_param("ss", $username, $currentusername);

	        $updateScavenger->execute();

			$updateSecondClass = $conn->prepare("UPDATE secondclass SET username=? WHERE username=?");

			$updateSecondClass->bind_param("ss", $username, $currentusername);

			$updateSecondClass->execute();

	        $date = time();

	        $cashmessage = "<b><font color=\'#00DD00\'>".$currentusername." just changed their username to ".$username."!</font></b><br />";

        	$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");

        	$query->bind_param("iss", $date, $currentusername, $cashmessage);

        	$query->execute() or die($conn->error);

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