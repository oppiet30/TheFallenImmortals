<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_name("fallenimmortals");
	session_start();
}
include('db.php');
include('varset.php');
include('active.php');

$Fixplayers = mysqli_query($conn, "UPDATE characters SET captcha='Inactive', captcha_time_limit='0'");

$whatTimeIsIt = time();
if($char['auto'] > "0" && $char['lastfight']+2 > $whatTimeIsIt){
	print "alert('Too fast sparkey!!');";
	die();
}

if(preg_match("/^javascript:/", $_SERVER['HTTP_REFERER'])){
	print "alert('Thats invalid, cheater!');";
	die();
}


if($char['security']=="1"){
    $setback = $conn->prepare("UPDATE characters SET security='0' WHERE id=?");
    $setback->bind_param("i", $char['id']);
    $setback->execute();
}

$securitytest = rand(1,200);
if($securitytest == "1" && $char['auto'] == "0"){
	$giveTest = $conn->prepare("UPDATE characters SET security='1' WHERE id=?");
	$giveTest->bind_param("i", $char['id']);
	$giveTest->execute();
}

$attacktype = $_POST['attackType'] ?? '';
$data = $data ?? "";
$enemyid = $char['enemyid'];
$enemytype = $char['enemytype'];
$enemylife = $char['enemylife'];

$getenemy = $conn->prepare("SELECT * FROM enemies WHERE id=?");
$getenemy->bind_param("i", $enemyid);
$getenemy->execute() or die($conn->error);
$enemy = $getenemy->get_result()->fetch_assoc();

$enemyname = $enemy['name'];
$enemylvl = $enemy['level'];
$enemystr = $enemy['level'] * "10";
$enemydex = $enemy['level'] * "10";

/*$getlocation = mysqli_query($conn, "SELECT * FROM areas WHERE name='".$charloc."'");
$location = mysqli_fetch_assoc($getlocation);
$locx = $location['sizex'];
$locy = $location['sizey'];*/

$data .= "<center>";

//include('enemyspecial.php');
if($enemylife > "0"){

	if($charlife > "0"){    //Player isn't dead
		include('attackbonuses.php');
		if($char['class'] == "Mage" || $char['class'] == "Mage II" || $char['class'] == "Mage III" || $char['class'] == "Mage IV" || $char['class'] == "Mage V" || $char['class'] == "Sorcerer" || $char['class'] == "Sorcerer II" || $char['class'] == "Sorcerer III" || $char['class'] == "Sorcerer IV" || $char['class'] == "Sorcerer V" || $char['class'] == "Elemental"){
			$initvalue = floor("75" * ($charcon / $enemydex));
		}else{
			$initvalue = floor("75" * ($chardex / $enemydex));
		}
		            //Check For Initiative
		$initnumber = mt_rand("1", "100");

		if($enemylife > "0"){    //Enemy exists
			if($initnumber > $initvalue){    //Enemy has initiative
				if($char['class'] == "Mage" || $char['class'] == "Mage II" || $char['class'] == "Mage III" || $char['class'] == "Mage IV" || $char['class'] == "Mage V" || $char['class'] == "Sorcerer" || $char['class'] == "Sorcerer II" || $char['class'] == "Sorcerer III" || $char['class'] == "Sorcerer IV" || $char['class'] == "Sorcerer V" || $char['class'] == "Elemental"){
					$hitval = floor("75" * ($enemydex / $charcon));
				}else{
					$hitval = floor("75" * ($enemydex / $chardex));
				}
				$hitnum = mt_rand("1", "100");
				if($hitnum <= $hitval){        //Enemy has hit the player
					$damagemin = floor($enemystr * "0.90");
					$damagemax = floor($enemystr * "1.10");
					$damageval = mt_rand($damagemin, $damagemax);
					if($damageval <= "0"){
						$damageval = "0";
					}
					$data .= "<font color=\'#DD2200\'>You have been hit for ".number_format($damageval)." damage!</font><br />";
					if($damageval >= $charlife){
						$charlife = "0";
					}
					else{
						$charlife -= $damageval;
					}
				}
                else{
                    $data .= "<font color=\'#CCAA00\'>The ".$enemyname." missed you!</font><br />";
                }
                
            }

            if($charlife > "0")        //Player isn't dead and attempts to fight back
            {
            	
            	if($char['class'] == "Mage" || $char['class'] == "Mage II" || $char['class'] == "Mage III" || $char['class'] == "Mage IV" || $char['class'] == "Mage V" || $char['class'] == "Sorcerer" || $char['class'] == "Sorcerer II" || $char['class'] == "Sorcerer III" || $char['class'] == "Sorcerer IV" || $char['class'] == "Sorcerer V" || $char['class'] == "Elemental"){
            		$hitval = floor("75" * ($charcon / $enemydex));
            	}else{
                	$hitval = floor("75" * ($chardex / $enemydex));
            	}
                $hitnum = mt_rand("1", "100");
                if($hitnum <= $hitval)        //Player has hit the enemy
                {
                	if($char['class'] == "Mage" || $char['class'] == "Mage II" || $char['class'] == "Mage III" || $char['class'] == "Mage IV" || $char['class'] == "Mage V" || $char['class'] == "Sorcerer" || $char['class'] == "Sorcerer II" || $char['class'] == "Sorcerer III" || $char['class'] == "Sorcerer IV" || $char['class'] == "Sorcerer V" || $char['class'] == "Elemental"){
                		$damagemin = floor($charint * "0.4");
                    	$damagemax = floor($charint * "0.5");
                	}else{
                    	$damagemin = floor($charstr * "0.4");
                    	$damagemax = floor($charstr * "0.5");
                	}
                    $damageval = mt_rand($damagemin, $damagemax);
                    //$damageval += $weapon;
                    if($damageval <= "0")
                    {
                        $damageval = "0";
                    }
                    $data .= "<font color=\'#00AACC\'>You have hit the ".$enemyname." for ".number_format($damageval)." damage!</font><br />";
                    if($damageval >= $enemylife)
                    {
                        $enemylife = "0";
                    }
                    else
                    {
                        $enemylife -= $damageval;
                    }
                }
                else
                {
                    $data .= "<font color=\'#00DD00\'>You missed the ".$enemyname."!</font><br />";
                }
            }

            if($enemylife > "0" && $initnumber <= $initvalue)    //Enemy failed initiation and hasn't been killed by the player
            {
            	
            	if($char['class'] == "Mage" || $char['class'] == "Mage II" || $char['class'] == "Mage III" || $char['class'] == "Mage IV" || $char['class'] == "Mage V" || $char['class'] == "Sorcerer" || $char['class'] == "Sorcerer II" || $char['class'] == "Sorcerer III" || $char['class'] == "Sorcerer IV" || $char['class'] == "Sorcerer V" || $char['class'] == "Elemental"){
            		$hitval = floor("75" * ($enemydex / $charcon));
            	}else{
                	$hitval = floor("75" * ($enemydex / $chardex));
            	}
                $hitnum = mt_rand("1", "100");
                if($hitnum <= $hitval)        //Enemy has hit the player
                {
                    $damagemin = floor($enemystr * "0.90");
                    $damagemax = floor($enemystr * "1.10");
                    $damageval = mt_rand($damagemin, $damagemax);
                    if($damageval <= "0")
                    {
                        $damageval = "0";
                    }
                    $data .= "<font color=\'#DD2200\'>You have been hit for ".number_format($damageval)." damage!</font><br />";
                    if($damageval >= $charlife)
                    {
                        $charlife = "0";
                    }
                    else
                    {
                        $charlife -= $damageval;
                    }
                }
                else
                {
                    $data .= "<font color=\'#CCAA00\'>The ".$enemyname." missed you!</font><br />";
                }
            }
            
        }

        $setlife = $conn->prepare("UPDATE characters SET life=? WHERE id=?");
        $setlife->bind_param("ii", $charlife, $_SESSION['userid']);
        $setlife->execute();
        if($enemylife < "1")    //Enemy has died and the player is rewarded for the fight
        {
            $taxTalk = "";
            if($charguild != "None")
            {
		$getMonsterRows = $conn->prepare("SELECT * FROM enemies WHERE level <=?");
		$getMonsterRows->bind_param("i", $enemylvl);
		$getMonsterRows->execute();
		$gettingMR = $getMonsterRows->get_result()->num_rows;
                $enemyexp = floor("1" + ($enemylvl * ("1" + ($guild['exp'] / "100")) / "1.8"));
                $enemygold = floor("15" * $gettingMR *("1" + ($guild['gold'] / "100")));
				$tax = ($guild['tax'] / "100");
				$tax = floor($tax * $enemygold);
				$enemygold = $enemygold - $tax;
				$addGuildGold = $conn->prepare("UPDATE guilds SET bank=bank+? WHERE name=?");
				$addGuildGold->bind_param("is", $tax, $charguild);
				$addGuildGold->execute();
				$taxTalk = "<br />Guild tax: ".$guild['tax']."% (-".number_format($tax)." gold)";

            }
            else
            {
		$getMonsterRows = $conn->prepare("SELECT * FROM enemies WHERE level <=?");
		$getMonsterRows->bind_param("i", $enemylvl);
		$getMonsterRows->execute();
		$gettingMR = $getMonsterRows->get_result()->num_rows;
                $enemyexp = floor("1" + ($enemylvl / "1.8"));   //1.5
                $enemygold = floor("15" * $gettingMR);  //
            }
			$getBonusTime = mysqli_query($conn, "SELECT * FROM bonus WHERE id='1'");
			$bonusTime = mysqli_fetch_assoc($getBonusTime);
			$currentTime = time();
			$gstrong = "";
			$endgStrong = "";
			$estrong = "";
			$endeStrong = "";
			$activeBonus = "";
			if($bonusTime['gold'] == "Yes" && $bonusTime['experationTime'] >= $currentTime)
			{
				$enemygold = $enemygold * 2;
				$gstrong = "<strong><font color=\"#00FF00\">";
				$endgStrong = " X2</font></strong>";
				$activeBonus = "<font color=\"#00FF00\">Bonus Time!</font>";
			}
			if($bonusTime['experience'] == "Yes" && $bonusTime['experationTime'] >= $currentTime)
			{
				$enemyexp = $enemyexp * 2;
				$estrong = "<strong><font color=\"#00FF00\">";
				$endeStrong = " X2</font></strong>";
				$activeBonus = "<font color=\"#00FF00\">Bonus Time!</font>";
			}
			if($bonusTime['experationTime'] <= $date && $bonusTime['experationTime'] != 0){
				$killBonusTime = mysqli_query($conn, "UPDATE bonus SET gold='No', experience='No', experationTime='0' WHERE id='1'");
				$messagechat = "<strong><font color=\"#00FF00\">Bonus time has ended.</font></strong><br />";
                $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                $query->bind_param("iss", $date, $char['username'], $messagechat);
                $query->execute();
			}
			if($bonusTime['experationTime'] != 0){
				$bonusTimeLeft = $bonusTime['experationTime'] - time();
				$bonusTimeLeft = floor($bonusTimeLeft / 60);
				$bonusTimeText = "<font color=\"#00FF00\">Minutes left: ".$bonusTimeLeft."</font>";
			}else{
				$bonusTimeText = "";
			}
            $newexp = $charexp + $enemyexp;
            $newgold = $chargold + $enemygold;
            $newblood = $char['blood'] + "1";
            $newkillstreak = $char['killstreak'] + "1";
			$lastFight = time();
            $rewards = $conn->prepare("UPDATE characters SET expacq=?, killstreak=?, gold=?, blood=?, enemylife='0', lastfight=? WHERE id=?");
            $rewards->bind_param("iiiiii", $newexp, $newkillstreak, $newgold, $newblood, $lastFight, $_SESSION['userid']);
            $rewards->execute();

            $data .= "<font color=\'#FFAA22\'>You have killed the ".$enemyname."!<br />You have gained ".$estrong."".number_format($enemyexp)."".$endeStrong." Experience and ".$gstrong."".number_format($enemygold)."".$endgStrong." Gold from the fight!".$taxTalk." <br />".$activeBonus." ".$bonusTimeText."</font><br />";
            include("killstreakreward.php");
            if($char['auto'] > "0"){
	            $charauto --;
				$query = $conn->prepare("UPDATE characters SET auto=? WHERE id=?");
				$query->bind_param("ii", $charauto, $_SESSION['userid']);
				$query->execute();
            }
			

            if($newexp >= $chartnl)    //Player has levelled up and has gained some stats
            {
                $data .= "<br />You gained a Level!<br />";
                $getclass = $conn->prepare("SELECT * FROM classes WHERE name=?");
                $getclass->bind_param("s", $charclass);
                $getclass->execute();
                $class = $getclass->get_result()->fetch_assoc();

                $newstr = $char['strength'] + $class['strength'];
                $newdex = $char['dexterity'] + $class['dexterity'];
                $newend = $char['endurance'] + $class['endurance'];
                $newint = $char['intelligence'] + $class['intelligence'];
                $newcon = $char['concentration'] + $class['concentration'];
                $statbonus = $charstatmulti * "2";
                $addstat = round($statbonus);
                $newstats = $charstats + $addstat;
                $newlvl = $charlvl + "1";
                if($char['refferal'] != "None"){
                    include('levelmilestone.php');
                    include('refferalbonus.php');
                }
                $newexp = $newexp - $chartnl;
                if($char['level'] < 10000){
                	$newtnl = $charlvl * "20";
                }elseif($char['level'] < 20000){
                	$newtnl = $charlvl * "40";
                }elseif($char['level'] < 30000){
                	$newtnl = $charlvl * "60";
                }elseif($char['level'] < 40000){
                	$newtnl = $charlvl * "80";
                }elseif($char['level'] < 50000){
                	$newtnl = $charlvl * "100";
                }elseif($char['level'] < 60000){
                	$newtnl = $charlvl * "110";
                }elseif($char['level'] < 70000){
                	$newtnl = $charlvl * "120";
                }elseif($char['level'] < 80000){
                	$newtnl = $charlvl * "130";
                }elseif($char['level'] < 90000){
                	$newtnl = $charlvl * "140";
                }elseif($char['level'] < 100000){
                	$newtnl = $charlvl * "150";
                }elseif($char['level'] < 110000){
                	$newtnl = $charlvl * "160";
                }elseif($char['level'] < 120000){
                	$newtnl = $charlvl * "180";
                }elseif($char['level'] < 130000){
                	$newtnl = $charlvl * "190";
                }elseif($char['level'] < 140000){
                	$newtnl = $charlvl * "200";
                }elseif($char['level'] < 150000){
                	$newtnl = $charlvl * "210";
                }elseif($char['level'] < 160000){
                	$newtnl = $charlvl * "220";
                }elseif($char['level'] < 170000){
                	$newtnl = $charlvl * "230";
                }elseif($char['level'] < 180000){
                	$newtnl = $charlvl * "230";
                }elseif($char['level'] < 190000){
                	$newtnl = $charlvl * "230";
                }elseif($char['level'] < 200000){
                	$newtnl = $charlvl * "230";
                }

                $data .= "+".$class['strength']." Strength, +".$class['dexterity']." Dexterity, +".$class['endurance']." Endurance,<br /> +".$class['intelligence']." Intelligence, +".$class['concentration']." Concentration and ".$addstat." Stat Points";

                $setchar = $conn->prepare("UPDATE characters SET strength=?, dexterity=?, endurance=?, intelligence=?, concentration=?, stats=?, level=?, expacq=?, expreq=? WHERE id=?");
                $setchar->bind_param("iiiiiiiiii", $newstr, $newdex, $newend, $newint, $newcon, $newstats, $newlvl, $newexp, $newtnl, $_SESSION['userid']);
                $setchar->execute();
            }

            //////////////////////////////////////////////////////////////
            //Note that the max number on Drop now displays 100,000.    //
            //This is to make more effect from bonuses to drop rates.   //
            //The "hit" number had been multiplied by 100 too.          //
            //////////////////////////////////////////////////////////////
            $drop = mt_rand("1","100000");
            if($charguild != "None")
            {
                //Character belongs to a Guild, apply the Guild Bonus to the drop rate
                $dropcount = floor("100" * ("1" + ($guild['itemdrop'] / "100")));
            }
            else
            {
                //Character does not belong to a Guild, set the default number
                $dropcount = "100";
            }
            if($drop <= $dropcount)    //Item Drop
            {
                include('itemdrop.php');
            }
			
			$location = "".$char['posx'].", ".$char['posy']."";
			$findAdventure = $conn->prepare("SELECT * FROM scavenger WHERE username=? AND location=? AND monster=?");
			$findAdventure->bind_param("sss", $char['username'], $location, $enemyname);
			$findAdventure->execute();
			$findAdventureResult = $findAdventure->get_result();
			$advent = $findAdventureResult->fetch_assoc();
			$collect = explode("/", $advent['collect'] ?? "0/0");
			if($findAdventureResult->num_rows > 0 && $collect[0] < $collect[1]){
				$drop = mt_rand("1","100000");
				$dropNeeded = 1000 - $char['scavenges'];
				if($drop <= $dropNeeded)    //Adventure item drop
            	{
            		if($advent['itemname'] == "Heads"){
            			$data .= "You pull the head of ".$enemyname." back and run a blade across its throat disengaging its head.<br />";
            		}elseif($advent['itemname'] == "Spirits"){
            			$data .= "With the Capture Box, you drain ".$enemyname."s spirit.<br />";
            		}elseif($advent['itemname'] == "Weapons"){
            			$data .= "You grab a weapon from ".$enemyname."s blood-drenched body.<br />";
            		}elseif($advent['itemname'] == "Followers"){
            			$data .= "After a smashing blow to ".$enemyname."s face, they are convinced you will be a good leader.<br />";
            		}
            		$collect0 =  $collect[0] + 1;
            		$collectcorrection = $collect0."/".$collect[1];
            		$updateCollect = $conn->prepare("UPDATE scavenger SET collect=? WHERE id=?");
            		$updateCollect->bind_param("si", $collectcorrection, $advent['id']);
            		$updateCollect->execute();
            		if($collect0 == $collect[1]){
            			$data .= "<b>Thats the last one!</b><br />";
            		}
            	}
			}
			
			$drop = mt_rand("1","100000");
			$base = 75 + $char['networth'];
			$findCash = mysqli_query($conn, "SELECT * FROM cashpot WHERE cash>'0'");
			$cash = mysqli_fetch_assoc($findCash);
            if($drop <= $base && $cash['cash'] > 0 && $char['networth'] > 0)    //Cash Drop
            {
                $data .= "<font color=\'#CCFF00\'><<<<< CASH FOUND >>>>>><b>!!!NETWORTH BONUS!!!</b></font><br />";
				$data .= "<img src=\'/images/cashDrop.png\'>";
                $cash = $cash['cash'] - 1;
                $addcash = $conn->prepare("UPDATE characters SET cash=cash+'1' WHERE id=?");
                $addcash->bind_param("i", $_SESSION['userid']);
                $addcash->execute();
                $removecash = mysqli_query($conn, "UPDATE cashpot SET cash=cash-'1'");
                $messagechat = "<strong><font color=\'#CCFF00\'><<<<< CASH FOUND >>>>>>".$char['username']." obtained One game cash! (".$cash." Left!)</font></strong><br />";
                $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                $query->bind_param("iss", $date, $char['username'], $messagechat);
                $query->execute();
            }
			
            $drop = mt_rand("1","100000");
            //Reinstate the default "hit" number for drops so that the
            //Guild Item Drop Bonus does not apply to other drop types too
            $dropcount = "100";
            if($drop <= "40")    //Statpoint Drop
            {
                $statdrop = mt_rand("1","30");
                $charstats += $statdrop;
                $data .= "<font color=\'#CCFF00\'>You obtain ".number_format($statdrop)." Stat Points! <b>BONUS</b>!</font><br />";
				$fillImg = "<img src=\'/images/statpointDrop.png\'>";
				print("fillDiv('rewardPopup','".$fillImg."');");
                $addpoints = $conn->prepare("UPDATE characters SET stats=? WHERE id=?");
                $addpoints->bind_param("ii", $charstats, $_SESSION['userid']);
                $addpoints->execute();
                $messagechat = "<strong><font color=\'#CCFF00\'>".$char['username']." obtained ".number_format($statdrop)." Stat Points as a bonus!</font></strong><br />";
                $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                $query->bind_param("iss", $date, $char['username'], $messagechat);
                $query->execute();
            }

            $drop = mt_rand("1","100000");
            if($drop <= "40")    //Gold Drop
            {
            	$multi = rand(1,50);
                $maxgolddrop = $charlvl * $multi;
                $golddrop = mt_rand(1,$maxgolddrop);
                $chargold += $golddrop;
                $data .= "<font color=\'#CCFF00\'>You obtain ".number_format($golddrop)." Gold! <b>BONUS</b>!</font><br />";
				$fillImg = "<img src=\'/images/goldDrop.png\'>";
				print("fillDiv('rewardPopup','".$fillImg."');");
                $addgold = $conn->prepare("UPDATE characters SET gold=? WHERE id=?");
                $addgold->bind_param("ii", $chargold, $_SESSION['userid']);
                $addgold->execute();
                $messagechat = "<strong><font color=\'#CCFF00\'>".$char['username']." obtained ".number_format($golddrop)." Gold as a bonus!</font></strong><br />";
                $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                $query->bind_param("iss", $date, $char['username'], $messagechat);
                $query->execute();
            }
            $drop = mt_rand("1","100000");
            if($drop <= "40")    //Blood Drop
            {
                $randomBlood = rand(1,100);
                $charblood = $char['blood'] + $randomBlood;
                $data .= "<font color=\'#CCFF00\'>You obtain ".number_format($randomBlood)." oz. of Blood! <b>BONUS</b>!</font><br />";
				$fillImg = "<img src=\'/images/bloodDrop.png\'>";
				print("fillDiv('rewardPopup','".$fillImg."');");
                $addgold = $conn->prepare("UPDATE characters SET blood=? WHERE id=?");
                $addgold->bind_param("ii", $charblood, $_SESSION['userid']);
                $addgold->execute();
                $messagechat = "<strong><font color=\'#CCFF00\'>".$char['username']." has hit the main artery of ".$enemyname." and gains ".number_format($randomBlood)." oz. of Blood as a bonus!</font></strong><br />";
                $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                $query->bind_param("iss", $date, $char['username'], $messagechat);
                $query->execute();
            }
            $drop = mt_rand("1","100000");
            if($drop <= "40")    //bag Drop
            {
                $randx = rand(1,100);
                $randy = rand(1,100);
                $addBag = $conn->prepare("INSERT INTO bagdrop(`name`, `posx`, `posy`) VALUES ('Bag', ?, ?)");
                $addBag->bind_param("ii", $randx, $randy);
                $addBag->execute();
                $messagechat = "<strong><font color=\'#CCFF00\'>A bag has fallen from the sky! It looks like it landed at ".$randx.", ".$randy."! GO GET IT!</font></strong><br />";
                $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                $query->bind_param("iss", $date, $char['username'], $messagechat);
                $query->execute();
            }
            $drop = mt_rand("1","100000");
            if($drop <= "20" && $char['mana'] < $char['intelligence'])    //mana Drop
            {
                $data .= "<font color=\'#CCFF00\'><b>You feel rejuvenated!</b></font><br />";
				$fillImg = "<img src=\'/images/manaDrop.png\'>";
				print("fillDiv('rewardPopup','".$fillImg."');");
                $addBag = $conn->prepare("UPDATE characters SET mana=? WHERE id=?");
                $addBag->bind_param("ii", $char['intelligence'], $_SESSION['userid']);
                $addBag->execute();
                $messagechat = "<strong><font color=\'#CCFF00\'>".$char['username']." has had their Mana recharged!</font></strong><br />";
                $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                $query->bind_param("iss", $date, $char['username'], $messagechat);
                $query->execute();
            }
            $drop = mt_rand("1","100000");
            if($drop <= "10" && $char['temple'] == "1")    //Temple Regenerate
            {
                $data .= "<font color=\'#CCFF00\'><b>You may visit the Temple again!</b></font><br />";
				$fillImg = "<img src=\'/images/templeReset.png\'>";
				print("fillDiv('rewardPopup','".$fillImg."');");
                $addBag = $conn->prepare("UPDATE characters SET temple='0' WHERE id=?");
                $addBag->bind_param("i", $_SESSION['userid']);
                $addBag->execute();
                $messagechat = "<strong><font color=\'#CCFF00\'>".$char['username']." has had their Temple reset and may donate again!</font></strong><br />";
                $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                $query->bind_param("iss", $date, $char['username'], $messagechat);
                $query->execute();
            }
            $drop = mt_rand("1","100000");
            if($drop <= "3" && $char['tradeskill'] < "1000")    //Trade Skill Increase
            {
                $data .= "<font color=\'#CCFF00\'><b>You have learned to better manage your money with the Shop!</b></font><br />";
				$fillImg = "<img src=\'/images/tradeskillDrop.png\'>";
				print("fillDiv('rewardPopup','".$fillImg."');");
                $increaseTradeSkill = $conn->prepare("UPDATE characters SET tradeskill=tradeskill+'1' WHERE id=?");
                $increaseTradeSkill->bind_param("i", $_SESSION['userid']);
                $increaseTradeSkill->execute();
            }
			$drop = mt_rand("1","100000");
            if($drop <= "3")    //Cash Drop
            {
				$nobility = explode("(", $char['nobility']);
				$nobilityLevel = str_replace(")", "", $nobility[1]);
				$nobilityRank = $nobility[0];
				$nobilityLevel = $nobilityLevel + 1;
				$newNp = $nobilityRank."(".$nobilityLevel.")";
                $data .= "<font color=\'#CCFF00\'><b>You robbed the Gula of her <strong>Cash</strong>!(+1NP)</b></font><br />";
				$fillImg = "<img src=\'/images/cashDrop.png\'>";
				print("fillDiv('rewardPopup','".$fillImg."');");
                $increaseTradeSkill = $conn->prepare("UPDATE characters SET cash=cash+'1', nobility=? WHERE id=?");
                $increaseTradeSkill->bind_param("si", $newNp, $_SESSION['userid']);
                $increaseTradeSkill->execute();
				$messagechat = "<font color=\'#CCFF00\'>".$char['username']." has robbed the Gula of her <strong>Cash</strong>!(+1NP)</font><br />";
                $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                $query->bind_param("iss", $date, $char['username'], $messagechat);
                $query->execute();
            }
            $drop = mt_rand("1","80000");
            if($drop <= "5")    //Demon Spawn
            {
            	$randBoss = rand(1,8);
            	if($randBoss == "1"){
            		$BossName = "Barbatos";
            		$BossPower = '1';
            	}
            	if($randBoss == "2"){
            		$BossName = "Incubus";
            		$BossPower = '1';
            	}
            	if($randBoss == "3"){
            		$BossName = "Eurynome";
            		$BossPower = '1';
            	}
            	if($randBoss == "4"){
            		$BossName = "Gula";
            		$BossPower = '1';
            	}
            	if($randBoss == "5"){
            		$BossName = "Barbatos Overlord";
            		$BossPower = '2';
            	}
            	if($randBoss == "6"){
            		$BossName = "Incubus Overlord";
            		$BossPower = '2';
            	}
            	if($randBoss == "7"){
            		$BossName = "Eurynome Overlord";
            		$BossPower = '2';
            	}
            	if($randBoss == "8"){
            		$BossName = "Gula Overlord";
            		$BossPower = '2';
            	}
				if($BossPower == "1"){
					$BossHealth = '5000000';
				}else{
					$BossHealth = $BossPower * '50000000';
				}
            	
				$randBossX = rand(1,100);
				$randBossY = rand(1,100);
                $data .= "<font color=\'#00FF20\'><strong>You have spawned <b>".$BossName."</b> from the depths of HELL! Location: (".$randBossX.", ".$randBossY.")</b></strong></font><br />";
				$fillImg = "<img src=\'/images/demonSpawn.png\'>";
				print("fillDiv('rewardPopup','".$fillImg."');");
                $messagechat = "<strong><font color=\'#00FF20\'>".$char['username']." has spawned ".$BossName." from the depths of HELL! Location: (".$randBossX.", ".$randBossY.")</font></strong><br />";
                $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
                $query->bind_param("iss", $date, $char['username'], $messagechat);
                $query->execute();
                $spawnDemon = $conn->prepare("INSERT INTO demons (`name`, `health`, `power`, `xpos`, `ypos`) VALUES (?, ?, ?, ?, ?)");
                $spawnDemon->bind_param("siiii", $BossName, $BossHealth, $BossPower, $randBossX, $randBossY);
                $spawnDemon->execute();
            }
			include("nobilityLvl.php");
        }
        elseif($charlife < "1")    //Player has died
        {
        	$enemylife = "0";
            $data .= "<font color=\'#FF0000\'>You have died!</font><br /><input type=\'button\' id=\'ressurect\' value=\'Ressurect\' onClick=\'ressurectChar();\' />";
            $killplayer = $conn->prepare("UPDATE characters SET life='0', auto='0', enemylife=? WHERE id=?");
            $killplayer->bind_param("ii", $enemylife, $_SESSION['userid']);
            $killplayer->execute();
			print("fillDiv('displayArea','".$data."');");
			die();
        }
        else    //Neither are dead and the form is given to attack again
        {
            $damageplayer = $conn->prepare("UPDATE characters SET life=?, enemylife=? WHERE id=?");
            $damageplayer->bind_param("iii", $charlife, $enemylife, $_SESSION['userid']);
            $damageplayer->execute();

			$data .= "Attacking Again! Please wait...";
            print("setTimeout('fightEnemy();', 2000);");
            
        }
        if($char['auto'] >= "1" && $charlife > "0" && $enemylife < "1")
		{
			print("setTimeout('runAway();', 2000);");
			$data .= "<center style=\'color:#CC0033;\'><p style=\'font-size: 16px;\'>Auto: ".$char['auto']."</p></center>";
			$data .= "<center><input type=\'button\' value=\'Stop Auto\' onclick=\'stopAuto();\'></center>";
		}
		if($char['auto'] < "1" && $enemylife <= "0"){
			$data = "<center><input type=\'button\' value=\'Fight More\' onclick=\'runAway();\' /></center>". $data;
		}
    }
    else
    {
        $data .= "<font color=\'#FF0000\'><b>Dead people cannot fight!</b></font><br /><input type=\'button\' id=\'ressurect\' value=\'Ressurect\' onClick=\'ressurectChar();\' />";
    }
}
else
{
    $data .= "This enemy is already dead. This will be seen as an attempt to hack the game and your account is now under investigation.";
}

print("fillDiv('displayArea','".$data."');");
include('updatestats.php');
?>