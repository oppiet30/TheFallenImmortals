<?php
session_name("fallenimmortals");
session_start();
include('db.php');

$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute() or die($conn->error);
$char = $getchar->get_result()->fetch_assoc();

if(isset($_POST['demonid'])){
    $findDemon = $conn->prepare("SELECT * FROM demons WHERE id=?");
    $findDemon->bind_param("i", $_POST['demonid']);
    $findDemon->execute();
    $findDemonResult = $findDemon->get_result();
    if($findDemonResult->num_rows >= "1"){
        $demon = $findDemonResult->fetch_assoc();
		if($char['level'] >= "10000" && $demon['power'] == "1"){
			die("alert('After level 10,000, you may only kill Overlord Demons.');");
		}
		
		if($demon['xpos'] != $char['posx'] || $demon['ypos'] != $char['posy']){
			print("alert('This demon is located at (".$demon['xpos'].", ".$demon['ypos'].").');");
			die();
		}
		$demonRel = explode(', ', $demon['relativeLoc']);
		$charRel = explode(', ', $char['relativeLoc']);
		$demonXtop = $demonRel[0]+32;
		$demonXbottom = $demonRel[0]-32;
		$demonYtop = $demonRel[1]+32;
		$demonYbottom = $demonRel[1]-32;
		
		if(($demonXtop >= $charRel[0] && $demonXbottom <= $charRel[0]) && ($demonYtop >= $charRel[1] && $demonYbottom <= $charRel[1])){
			
		}else{
			die("alert('You must move closer to the demon to attack it!');");
		}
        
        $characterChance = mt_rand(1, 3);
        if($characterChance == "1" && $demon['health'] > "0"){  //Character attacks demon
        	if($char['class'] == "Mage" || $char['class'] == "Mage II" || $char['class'] == "Mage III" || $char['class'] == "Mage IV" || $char['class'] == "Mage V" || $char['class'] == "Sorcerer" || $char['class'] == "Sorcerer II" || $char['class'] == "Sorcerer III" || $char['class'] == "Sorcerer IV" || $char['class'] == "Sorcerer V" || $char['class'] == "Elemental"){
            	$damagemin = floor($charint * "0.4");
        		$damagemax = floor($charint * "0.5");
        	}else{
        		$damagemin = floor($charstr * "0.4");
        		$damagemax = floor($charstr * "0.5");
        	}
            
            $damageval = mt_rand($damagemin, $damagemax);
            $demonNewHealth = $demon['health'] - $damageval;
            $hurtdemon = $conn->prepare("UPDATE demons SET health=health-? WHERE id=?");
            $hurtdemon->bind_param("ii", $damageval, $demon['id']);
            $hurtdemon->execute();
            $data .= "You hit ".$demon['name']." for ".number_format($damageval)." damage!<br />";
            $data .= "".$demon['name']." has ".number_format($demonNewHealth)." left!<br />";
            if($demon['health'] <= "0" || $demonNewHealth <= "0"){
                $data .= "You killed ".$demon['name']."!<br />";
                if($demon['name'] == "Barbatos"){
                    $reward = $conn->prepare("UPDATE characters SET stats=stats+'250' WHERE id=?");
                    $reward->bind_param("i", $_SESSION['userid']);
                    $reward->execute();
                    $data .= "You gain 250 Stat Points in your success!<br />";
                    $messagechat = "<strong><font color=\'#FF0000\'>".$char['username']." killed ".$demon['name']." and gained 250 Stat Points!</font></strong><br />";
                    $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                    $query->bind_param("iss", $date, $char['username'], $messagechat);
                    $query->execute();
                }elseif($demon['name'] == "Barbatos Overlord"){
                    $reward = $conn->prepare("UPDATE characters SET stats=stats+'1000' WHERE id=?");
                    $reward->bind_param("i", $_SESSION['userid']);
                    $reward->execute();
                    $data .= "You gain 1,000 Stat Points in your success!<br />";
                    $messagechat = "<strong><font color=\'#FF0000\'>".$char['username']." killed ".$demon['name']." and gained 1,000 Stat Points!</font></strong><br />";
                    $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                    $query->bind_param("iss", $date, $char['username'], $messagechat);
                    $query->execute();
                }elseif($demon['name'] == "Incubus"){
                    $reward = $conn->prepare("UPDATE characters SET gold=gold+'1500000' WHERE id=?");
                    $reward->bind_param("i", $_SESSION['userid']);
                    $reward->execute();
                    $data .= "You gain 1,500,000 Gold in your success!<br />";
                    $messagechat = "<strong><font color=\'#FF0000\'>".$char['username']." killed ".$demon['name']." and gained 1,500,000 Gold!</font></strong><br />";
                    $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                    $query->bind_param("iss", $date, $char['username'], $messagechat);
                    $query->execute();
                }elseif($demon['name'] == "Incubus Overlord"){
                    $reward = $conn->prepare("UPDATE characters SET gold=gold+'10000000' WHERE id=?");
                    $reward->bind_param("i", $_SESSION['userid']);
                    $reward->execute();
                    $data .= "You gain 10,000,000 Gold in your success!<br />";
                    $messagechat = "<strong><font color=\'#FF0000\'>".$char['username']." killed ".$demon['name']." and gained 10,000,000 Gold!</font></strong><br />";
                    $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                    $query->bind_param("iss", $date, $char['username'], $messagechat);
                    $query->execute();
                }elseif($demon['name'] == "Eurynome"){
                    $reward = $conn->prepare("UPDATE characters SET blood=blood+'300' WHERE id=?");
                    $reward->bind_param("i", $_SESSION['userid']);
                    $reward->execute();
                    $data .= "You gain 300 oz. of Blood in your success!<br />";
                    $messagechat = "<strong><font color=\'#FF0000\'>".$char['username']." killed ".$demon['name']." and gained 300 oz. of Blood!</font></strong><br />";
                    $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                    $query->bind_param("iss", $date, $char['username'], $messagechat);
                    $query->execute();
                }elseif($demon['name'] == "Eurynome Overlord"){
                    $reward = $conn->prepare("UPDATE characters SET blood=blood+'1200' WHERE id=?");
                    $reward->bind_param("i", $_SESSION['userid']);
                    $reward->execute();
                    $data .= "You gain 1,200 oz. of Blood in your success!<br />";
                    $messagechat = "<strong><font color=\'#FF0000\'>".$char['username']." killed ".$demon['name']." and gained 1,200 oz. of Blood!</font></strong><br />";
                    $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                    $query->bind_param("iss", $date, $char['username'], $messagechat);
                    $query->execute();
                }elseif($demon['name'] == "Gula"){
                    $reward = $conn->prepare("UPDATE characters SET cash=cash+'1' WHERE id=?");
                    $reward->bind_param("i", $_SESSION['userid']);
                    $reward->execute();
                    $data .= "You gain 1 Cash in your success!<br />";
                    $messagechat = "<strong><font color=\'#FF0000\'>".$char['username']." killed ".$demon['name']." and gained 1 Cash!</font></strong><br />";
                    $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                    $query->bind_param("iss", $date, $char['username'], $messagechat);
                    $query->execute();
                }elseif($demon['name'] == "Gula Overlord"){
                    $reward = $conn->prepare("UPDATE characters SET cash=cash+'3' WHERE id=?");
                    $reward->bind_param("i", $_SESSION['userid']);
                    $reward->execute();
                    $data .= "You gain 3 Cash in your success!<br />";
                    $messagechat = "<strong><font color=\'#FF0000\'>".$char['username']." killed ".$demon['name']." and gained 3 Cash!</font></strong><br />";
                    $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                    $query->bind_param("iss", $date, $char['username'], $messagechat);
                    $query->execute();
                }
            }else{
                $demondamsmall = floor($demon['power'] * "500000");
                $demondambig = floor($demon['power'] * "5000000");
                $demondamage = mt_rand($demondamsmall, $demondambig);
                $data .= "".$demon['name']." hits you for ".number_format($demondamage)." damage!<br />";
                $data .= "".$demon['name']." has ".number_format($demon['health'])." left!<br />";
                $updateCharHealth = $conn->prepare("UPDATE characters SET life=life-? WHERE id=?");
                $updateCharHealth->bind_param("ii", $demondamage, $_SESSION['userid']);
                $updateCharHealth->execute();
                $charhealth = $char['life'] - $demondamage;
                if($char['life'] <= "0" || $charhealth <= "0"){
                    $data .= "<font color=\'#FF0000\'>You have died, but the fierce will to defeat this demon has brought you back to life!</font><br />";
                    $killplayer = $conn->prepare("UPDATE characters SET life=? WHERE id=?");
                    $killplayer->bind_param("ii", $char['endurance'], $_SESSION['userid']);
                    $killplayer->execute();
                    $data .= "</center>";
                }else{
                    $data .= "</center>";
                }
            }
        }elseif($demon['health'] > "0"){   //Character misses demon
            $data .= "You fail to hit ".$demon['name']."!<br />";
            $demondamsmall = floor($demon['power'] * "500000");
            $demondambig = floor($demon['power'] * "5000000");
            $demondamage = mt_rand($demondamsmall, $demondambig);
            $data .= "".$demon['name']." hits you for ".number_format($demondamage)." damage!<br />";
            $data .= "".$demon['name']." has ".number_format($demon['health'])." left!<br />";
            $charhealth = $char['life'] - $demondamage;
            if($char['life'] <= "0" || $charhealth <= "0"){
                $data .= "<font color=\'#FF0000\'>You have died, but the fierce will to defeat this demon has brought you back to life!</font><br />";
                $killplayer = $conn->prepare("UPDATE characters SET life=? WHERE id=?");
                $killplayer->bind_param("ii", $char['endurance'], $_SESSION['userid']);
                $killplayer->execute();
                $data .= "</center>";
            }else{
                
            }
        }else{
            $data .= "The demon is dead!<br />";
        }
    }else{
        $data .= "The demon is dead!<br />";
    }
}


print("fillDiv('travelDesc','".$data."');");
include('updatestats.php');
?>