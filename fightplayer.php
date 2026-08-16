<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include('varset.php');
include('active.php');
include('attackbonuses.php');
$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute();
$char = $getchar->get_result()->fetch_assoc();
$date = time();

$findDuel = $conn->prepare("SELECT * FROM duelground WHERE `tousername`=? OR `fromusername`=?");
$findDuel->bind_param("ss", $char['username'], $char['username']);
$findDuel->execute();
$findDuelResult = $findDuel->get_result();
if($findDuelResult->num_rows == 0){
	print("alert('You are not in a duel!');");
}else{
	$duel = $findDuelResult->fetch_assoc();
	if($duel['status'] == "Requesting" || $duel['turn'] != $char['username']){
		print("alert('It is not your turn to duel or the duel hasnt even started!');");
	}else{
		if($charlife <= "0"){
			print("alert('You were slaughtered.');");
			$updateDuel = $conn->prepare("DELETE FROM duelground WHERE `id`=?");
			$updateDuel->bind_param("i", $duel['id']);
			$updateDuel->execute();
		}else{
			if($char['username'] == $duel['tousername']){
				$findOponent = $conn->prepare("SELECT * FROM characters WHERE username=?");
				$findOponent->bind_param("s", $duel['fromusername']);
				$findOponent->execute();
				$oponent = $findOponent->get_result()->fetch_assoc();
			}else{
				$findOponent = $conn->prepare("SELECT * FROM characters WHERE username=?");
				$findOponent->bind_param("s", $duel['tousername']);
				$findOponent->execute();
				$oponent = $findOponent->get_result()->fetch_assoc();
			}
			require('oponentvarset.php');
			require('oponentattackbonuses.php');
			if($oponentlife <= "0"){
				print("alert('".$oponentname." was slaughtered.');");
				$updateDuel = $conn->prepare("DELETE FROM duelground WHERE `id`=?");
				$updateDuel->bind_param("i", $duel['id']);
				$updateDuel->execute();
			}else{
				
				if($char['class'] == "Mage" || $char['class'] == "Mage II" || $char['class'] == "Mage III" || $char['class'] == "Mage IV" || $char['class'] == "Mage V" || $char['class'] == "Sorcerer" || $char['class'] == "Sorcerer II" || $char['class'] == "Sorcerer III" || $char['class'] == "Sorcerer IV" || $char['class'] == "Sorcerer V" || $char['class'] == "Elemental"){
					$initvalue = floor("75" * ($charcon / $oponentdex));
				}else{
					$initvalue = floor("75" * ($chardex / $oponentdex));
				}
				$initnumber = mt_rand("1", "100");
				
				if($initnumber > $initvalue){ //You missed
					$messagechat = "<strong><font color=\'#FF3300\'>You <span style=\'color: red;\'><i><strong>miss</strong></i></span> ".$oponentname.".</font></strong><br />";
					$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)");
					$query->bind_param("iss", $date, $messagechat, $char['username']);
					$query->execute();

					$messagechat = "<strong><font color=\'#FF3300\'>".$char['username']." <span style=\'color: green;\'><i><strong>missed</strong></i></span> you! <a href=\'javascript: attackFight();\'>Attack</a>!</font></strong><br />";
					$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)");
					$query->bind_param("iss", $date, $messagechat, $oponentname);
					$query->execute();

					$updateDuel = $conn->prepare("UPDATE duelground SET turn=?, time=? WHERE id=?");
					$updateDuel->bind_param("sii", $oponentname, $date, $duel['id']);
					$updateDuel->execute();

				}else{ // You hit
				
					if($char['class'] == "Mage" || $char['class'] == "Mage II" || $char['class'] == "Mage III" || $char['class'] == "Mage IV" || $char['class'] == "Mage V" || $char['class'] == "Sorcerer" || $char['class'] == "Sorcerer II" || $char['class'] == "Sorcerer III" || $char['class'] == "Sorcerer IV" || $char['class'] == "Sorcerer V" || $char['class'] == "Elemental"){
                		$damagemin = floor($charint * "0.1");
                    	$damagemax = floor($charint * "0.5");
                	}else{
                    	$damagemin = floor($charstr * "0.1");
                    	$damagemax = floor($charstr * "0.5");
                	}
                	$damageval = mt_rand($damagemin, $damagemax); // amount of damage
                	$oponentlife = $oponentlife - $damageval;
                	
                	$messagechat = "<strong><font color=\'#FF3300\'>You <span style=\'color: green;\'><i><strong>hit</strong></i></span> ".$oponentname." for <span style=\'color: green;\'><i><strong>".number_format($damageval)."</strong></i></span> damage!!!</font></strong><br />";
					$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)");
					$query->bind_param("iss", $date, $messagechat, $char['username']);
					$query->execute();

                	$messagechat = "<strong><font color=\'#FF3300\'>".$charname." <span style=\'color: red;\'><i><strong>hit</strong></i></span> you for <span style=\'color: red;\'><i><strong>".number_format($damageval)."</strong></i></span> damage!!!</font></strong><br />";
					$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)");
					$query->bind_param("iss", $date, $messagechat, $oponentname);
					$query->execute();

                	if($oponentlife <= "0"){ //oponent is dead
                		$messagechat = "<strong><font color=\'#FF3300\'>You <span style=\'color: green;\'><i><strong>slaughtered</strong></i></span> ".$oponentname."!!! Congratulations!</font></strong><br />";
						$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)");
						$query->bind_param("iss", $date, $messagechat, $char['username']);
						$query->execute();

						$yourRatio = explode('/', $char['duelratio']);
						$yourRatio[0] = $yourRatio[0] + 1;
						$newRatio = $yourRatio[0]."/".$yourRatio[1];
						$updateRatio = $conn->prepare("UPDATE characters SET duelratio=? WHERE username=?");
						$updateRatio->bind_param("ss", $newRatio, $char['username']);
						$updateRatio->execute();

						$messagechat = "<strong><font color=\'#FF3300\'>".$charname." <span style=\'color: red;\'><i><strong>slaughtered</strong></i></span> you. Sorry for your loss...</font></strong><br />";
						$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)");
						$query->bind_param("iss", $date, $messagechat, $oponentname);
						$query->execute();

						$oponentRatio = explode('/', $oponent['duelratio']);
						$oponentRatio[1] = $oponentRatio[1] + 1;
						$newRatio = $oponentRatio[0]."/".$oponentRatio[1];
						$updateRatio = $conn->prepare("UPDATE characters SET duelratio=? WHERE username=?");
						$updateRatio->bind_param("ss", $newRatio, $oponent['username']);
						$updateRatio->execute();

						$endDuel = $conn->prepare("DELETE FROM duelground WHERE id=?");
						$endDuel->bind_param("i", $duel['id']);
						$endDuel->execute();
                	}else{ //The duel goes on...
                		$messagechat = "<strong><font color=\'#FF3300\'>".$oponentname."is initiating the next round...</font></strong><br />";
						$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)");
						$query->bind_param("iss", $date, $messagechat, $char['username']);
						$query->execute();

						$messagechat = "<strong><font color=\'#FF3300\'>You still stand... <a href=\'javascript: attackFight();\'>Attack</a>!</font></strong><br />";
						$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '4', 'PM', ?, ?)");
						$query->bind_param("iss", $date, $messagechat, $oponentname);
						$query->execute();

						$changeDuelSides = $conn->prepare("UPDATE duelground SET turn=?, time=? WHERE id=?");
						$changeDuelSides->bind_param("sii", $oponentname, $date, $duel['id']);
						$changeDuelSides->execute();
                	}

                	$updateOponent = $conn->prepare("UPDATE characters SET life=? WHERE username=?");
                	$updateOponent->bind_param("is", $oponentlife, $oponentname);
                	$updateOponent->execute();
				}
				
			}
		}
	}
}
include('updatestats.php');
?>