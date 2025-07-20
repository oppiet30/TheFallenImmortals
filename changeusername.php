<?php

session_name("icsession");

session_start();

include('db.php');

$getchar = mysqli_query($conn, "SELECT * FROM characters WHERE id='".$_SESSION['userid']."'") or die(mysql_error());

$char = mysql_fetch_assoc($getchar);

$display = "";



if($_POST['newUsername'] != NULL || $_POST['newUsername'] != "" && $char['changeusername'] == "1"){

	$username = $_POST['newUsername'];

	$currentusername = $char['username'];

	if(preg_match("/^[a-z0-9_]+$/i", $username))

	{

	    $getuser = mysqli_query($conn, "SELECT * FROM characters WHERE username='".$username."'");

	    if(mysql_num_rows($getuser) != "1")    //Username does not exist

	    {

	        $display .= "Username: <font color=\'#00DD00\'>OK</font><br />";

	        $guildLeaderCheck = mysqli_query($conn, "SELECT * FROM guilds WHERE leader='".$char['username']."'");

	        if(mysql_num_rows($guildLeaderCheck) == "1"){

	        	$updateGuildLeader = mysqli_query($conn, "UPDATE guilds SET leader='".$username."' WHERE leader='".$char['username']."'");

	        }

	        $guildColeaderCheck = mysqli_query($conn, "SELECT * FROM guilds WHERE coleader='".$char['username']."'");

	        if(mysql_num_rows($guildColeaderCheck) == "1"){

	        	$updateGuildColeader = mysqli_query($conn, "UPDATE guilds SET coleader='".$username."' WHERE coleader='".$char['username']."'");

	        }

	        $guildCaptianCheck = mysqli_query($conn, "SELECT * FROM guilds WHERE captian='".$char['username']."'");

	        if(mysql_num_rows($guildCaptianCheck) == "1"){

	        	$updateGuildCaptian = mysqli_query($conn, "UPDATE guilds SET captian='".$username."' WHERE captian='".$char['username']."'");

	        }

	        $updateInventory = mysqli_query($conn, "UPDATE inventory SET username='".$username."' WHERE username='".$char['username']."'");

	        $updateTrade = mysqli_query($conn, "UPDATE trade SET fromplayer='".$username."' WHERE fromplayer='".$char['username']."'");

	        $updateUser = mysqli_query($conn, "UPDATE characters SET changeusername='0', username='".$username."' WHERE id='".$_SESSION['userid']."'");

	        $updateScavenger = mysqli_query($conn, "UPDATE scavenger SET username='".$username."' WHERE username='".$currentusername."'");
			
			$updateSecondClass = mysqli_query($conn, "UPDATE secondclass SET username='".$username."' WHERE username='".$currentusername."'");

	        $date = time();

	        $cashmessage = "<b><font color=\'#00DD00\'>".$currentusername." just changed their username to ".$username."!</font></b><br />";

        	$query = mysqli_query($conn, "INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`)VALUES('".$date."', '3', '".$currentusername."', '".$cashmessage."', 'Chatroom')") or die(mysql_error());

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