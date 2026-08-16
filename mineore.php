<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include('varset.php');
$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute() or die($conn->error);
$char = $getchar->get_result()->fetch_assoc();

$data = "";
$current = time();
if($char['lastmine']+1 < $current){

	$findOre = $conn->prepare("SELECT * FROM ore WHERE xpos=? AND ypos=?");
	$findOre->bind_param("ii", $char['posx'], $char['posy']);
	$findOre->execute();
	$findOreResult = $findOre->get_result();
	$there = $findOreResult->num_rows;
	if($there > "0"){
		$ore = $findOreResult->fetch_assoc();
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
					$addCopper = $conn->prepare("UPDATE characters SET copperore=copperore+'1' WHERE username=?");
					$addCopper->bind_param("s", $char['username']);
					$addCopper->execute();
					$amount = $amount - 1;
					$minusTheOre = $conn->prepare("UPDATE ore SET amount=? WHERE id=?");
					$minusTheOre->bind_param("ii", $amount, $ore['id']);
					$minusTheOre->execute();
				}else{
					$data .="You fail to find any ore... Keep trying, there is still some in this location!<br />";
				}
				
				$nextMining = ($char['mininglevel']*1000) + 10000;
				$randomNextLevel = rand(1,$nextMining);
				if($randomNextLevel <= 200){
					$data .= "<<<---Your level in Mining has increased!--->>><br />";
					$updateMiningLevel = $conn->prepare("UPDATE characters SET mininglevel=mininglevel+'1' WHERE username=?");
				$updateMiningLevel->bind_param("s", $char['username']);
				$updateMiningLevel->execute();
				}
			
			
			
			}
			if($char['mininglevel'] >= 25 && $char['mininglevel'] < 65){
				
				
				$findCopper = rand(1,10000);
				$findIron = rand(1,12500);
				if($char['mininglevel']+200 >= $findCopper){
					$data .="You found Copper!<br />";
					$addCopper = $conn->prepare("UPDATE characters SET copperore=copperore+'1' WHERE username=?");
					$addCopper->bind_param("s", $char['username']);
					$addCopper->execute();
					$amount = $amount - 1;
					$minusTheOre = $conn->prepare("UPDATE ore SET amount=? WHERE id=?");
					$minusTheOre->bind_param("ii", $amount, $ore['id']);
					$minusTheOre->execute();
				}elseif($char['mininglevel']+200 >= $findIron){
					$data .="You found Iron!<br />";
					$addCopper = $conn->prepare("UPDATE characters SET ironore=ironore+'1' WHERE username=?");
					$addCopper->bind_param("s", $char['username']);
					$addCopper->execute();
					$amount = $amount - 1;
					$minusTheOre = $conn->prepare("UPDATE ore SET amount=? WHERE id=?");
					$minusTheOre->bind_param("ii", $amount, $ore['id']);
					$minusTheOre->execute();
				}else{
					$data .="You fail to find any ore... Keep trying, there is still some in this location!<br />";
				}
				
				$nextMining = ($char['mininglevel']*1000) + 10000;
				$randomNextLevel = rand(1,$nextMining);
				if($randomNextLevel <= 200){
					$data .= "<<<---Your level in Mining has increased!--->>><br />";
					$updateMiningLevel = $conn->prepare("UPDATE characters SET mininglevel=mininglevel+'1' WHERE username=?");
				$updateMiningLevel->bind_param("s", $char['username']);
				$updateMiningLevel->execute();
				}
				
				
				
			}
			if($char['mininglevel'] >= 65){
				
				
				$findCopper = rand(1,10000);
				$findIron = rand(1,12500);
				$findSteel = rand(1,17500);
				if($char['mininglevel']+200 >= $findCopper){
					$data .="You found Copper!<br />";
					$addCopper = $conn->prepare("UPDATE characters SET copperore=copperore+'1' WHERE username=?");
					$addCopper->bind_param("s", $char['username']);
					$addCopper->execute();
					$amount = $amount - 1;
					$minusTheOre = $conn->prepare("UPDATE ore SET amount=? WHERE id=?");
					$minusTheOre->bind_param("ii", $amount, $ore['id']);
					$minusTheOre->execute();
				}
				if($char['mininglevel']+200 >= $findIron){
					$data .="You found Iron!<br />";
					$addCopper = $conn->prepare("UPDATE characters SET ironore=ironore+'1' WHERE username=?");
					$addCopper->bind_param("s", $char['username']);
					$addCopper->execute();
					$amount = $amount - 1;
					$minusTheOre = $conn->prepare("UPDATE ore SET amount=? WHERE id=?");
					$minusTheOre->bind_param("ii", $amount, $ore['id']);
					$minusTheOre->execute();
				}
				if($char['mininglevel']+200 >= $findSteel){
					$data .="You found Steel!<br />";
					$addCopper = $conn->prepare("UPDATE characters SET steelore=steelore+'1' WHERE username=?");
					$addCopper->bind_param("s", $char['username']);
					$addCopper->execute();
					$amount = $amount - 1;
					$minusTheOre = $conn->prepare("UPDATE ore SET amount=? WHERE id=?");
					$minusTheOre->bind_param("ii", $amount, $ore['id']);
					$minusTheOre->execute();
				}
				if($char['mininglevel']+200 < $findCopper && $char['mininglevel']+200 < $findIron && $char['mininglevel']+200 < $findSteel){
					$data .="You fail to find any ore... Keep trying, there is still some in this location!<br />";
				}
				
				$nextMining = ($char['mininglevel']*1000) + 10000;
				$randomNextLevel = rand(1,$nextMining);
				if($randomNextLevel <= 200){
					$data .= "<<<---Your level in Mining has increased!--->>><br />";
					$updateMiningLevel = $conn->prepare("UPDATE characters SET mininglevel=mininglevel+'1' WHERE username=?");
				$updateMiningLevel->bind_param("s", $char['username']);
				$updateMiningLevel->execute();
				}
				
				
				
			}
			
			if($amount <= 0){
				$xloc = rand(1,100);
				$yloc = rand(1,100);
				$newAmount = rand(10,20);
				$updateTheOre = $conn->prepare("UPDATE ore SET xpos=?, ypos=?, amount=? WHERE id=?");
				$updateTheOre->bind_param("iiii", $xloc, $yloc, $newAmount, $ore['id']);
				$updateTheOre->execute();
				$data .= "That was the last of the ore here.";
			}
			
			$date = time();
			$updateLastMine = $conn->prepare("UPDATE characters SET lastmine=? WHERE username=?");
			$updateLastMine->bind_param("is", $date, $char['username']);
			$updateLastMine->execute();
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