<?php
session_name("icsession");
session_start();
include('db.php');
include('varset.php');
$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);

$data = "";
$current = time();
if($char['lastmine']+1 < $current){
	
	$findOre = db_query("SELECT * FROM ore WHERE xpos=? AND ypos=?", [$char['posx'], $char['posy']]);
	$there = db_num_rows($findOre);
	if($there > "0"){
		$ore = db_fetch_assoc($findOre);
		$oreRel = explode(', ', $ore['relativeLoc']);
		$charRel = explode(', ', $char['relativeLoc']);
		$oreXtop = $oreRel[0]+32;
		$oreXbottom = $oreRel[0]-32;
		$oreYtop = $oreRel[1]+32;
		$oreYbottom = $oreRel[1]-32;
		
		if(($oreXtop >= $charRel[0] && $oreXbottom <= $charRel[0]) && ($oreYtop >= $charRel[1] && $oreYbottom <= $charRel[1])){
			
		}else{
			die("alert('You must move closer to the ore.');");
		}
		if($ore['amount'] > 0){
			$amount = $ore['amount'];
			if($char['mininglevel'] < 25){
				$findCopper = rand(1,10000);
				if($char['mininglevel']+200 >= $findCopper){
					$data .="You found Copper!<br />";
					$addCopper = db_query("UPDATE characters SET copperore=copperore+'1' WHERE username=?", [$char['username']]);
					$amount = $amount - 1;
					$minusTheOre = db_query("UPDATE ore SET amount=? WHERE id=?", [$amount, $ore['id']]);
				}else{
					$data .="You fail to find any ore... Keep trying, there is still some in this location!<br />";
				}
				
				$nextMining = ($char['mininglevel']*1000) + 10000;
				$randomNextLevel = rand(1,$nextMining);
				if($randomNextLevel <= 200){
					$data .= "<<<---Your level in Mining has increased!--->>><br />";
					$updateMiningLevel = db_query("UPDATE characters SET mininglevel=mininglevel+'1' WHERE username=?", [$char['username']]);
				}
			
			
			
			}
			if($char['mininglevel'] >= 25 && $char['mininglevel'] < 65){
				
				
				$findCopper = rand(1,10000);
				$findIron = rand(1,12500);
				if($char['mininglevel']+200 >= $findCopper){
					$data .="You found Copper!<br />";
					$addCopper = db_query("UPDATE characters SET copperore=copperore+'1' WHERE username=?", [$char['username']]);
					$amount = $amount - 1;
					$minusTheOre = db_query("UPDATE ore SET amount=? WHERE id=?", [$amount, $ore['id']]);
				}elseif($char['mininglevel']+200 >= $findIron){
					$data .="You found Iron!<br />";
					$addCopper = db_query("UPDATE characters SET ironore=ironore+'1' WHERE username=?", [$char['username']]);
					$amount = $amount - 1;
					$minusTheOre = db_query("UPDATE ore SET amount=? WHERE id=?", [$amount, $ore['id']]);
				}else{
					$data .="You fail to find any ore... Keep trying, there is still some in this location!<br />";
				}
				
				$nextMining = ($char['mininglevel']*1000) + 10000;
				$randomNextLevel = rand(1,$nextMining);
				if($randomNextLevel <= 200){
					$data .= "<<<---Your level in Mining has increased!--->>><br />";
					$updateMiningLevel = db_query("UPDATE characters SET mininglevel=mininglevel+'1' WHERE username=?", [$char['username']]);
				}
				
				
				
			}
			if($char['mininglevel'] >= 65){
				
				
				$findCopper = rand(1,10000);
				$findIron = rand(1,12500);
				$findSteel = rand(1,17500);
				if($char['mininglevel']+200 >= $findCopper){
					$data .="You found Copper!<br />";
					$addCopper = db_query("UPDATE characters SET copperore=copperore+'1' WHERE username=?", [$char['username']]);
					$amount = $amount - 1;
					$minusTheOre = db_query("UPDATE ore SET amount=? WHERE id=?", [$amount, $ore['id']]);
				}
				if($char['mininglevel']+200 >= $findIron){
					$data .="You found Iron!<br />";
					$addCopper = db_query("UPDATE characters SET ironore=ironore+'1' WHERE username=?", [$char['username']]);
					$amount = $amount - 1;
					$minusTheOre = db_query("UPDATE ore SET amount=? WHERE id=?", [$amount, $ore['id']]);
				}
				if($char['mininglevel']+200 >= $findSteel){
					$data .="You found Steel!<br />";
					$addCopper = db_query("UPDATE characters SET steelore=steelore+'1' WHERE username=?", [$char['username']]);
					$amount = $amount - 1;
					$minusTheOre = db_query("UPDATE ore SET amount=? WHERE id=?", [$amount, $ore['id']]);
				}
				if($char['mininglevel']+200 < $findCopper && $char['mininglevel']+200 < $findIron && $char['mininglevel']+200 < $findSteel){
					$data .="You fail to find any ore... Keep trying, there is still some in this location!<br />";
				}
				
				$nextMining = ($char['mininglevel']*1000) + 10000;
				$randomNextLevel = rand(1,$nextMining);
				if($randomNextLevel <= 200){
					$data .= "<<<---Your level in Mining has increased!--->>><br />";
					$updateMiningLevel = db_query("UPDATE characters SET mininglevel=mininglevel+'1' WHERE username=?", [$char['username']]);
				}
				
				
				
			}
			
			if($amount <= 0){
				$xloc = rand(1,100);
				$yloc = rand(1,100);
				$newAmount = rand(10,20);
				$updateTheOre =  db_query("UPDATE ore SET xpos=?, ypos=?, amount=? WHERE id=?", [$xloc, $yloc, $newAmount, $ore['id']]);
				$data .= "That was the last of the ore here.";
			}
			
			$date = time();
			$updateLastMine = db_query("UPDATE characters SET lastmine=? WHERE username=?", [$date, $char['username']]);
			include('updatestats.php');
		}else{
			$data .= "No ore left.";
		}
	}else{
		$data .= "There is no ore at this location!";
	}

}else{
	$data .= "You are resting your arms, try again in a second.";
}
print("fillDiv('travelDesc','".$data."');");
?>