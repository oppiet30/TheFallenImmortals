<?php 
session_name("fallenimmortals");
session_start();
include('db.php');

if($_POST['ore'] != NULL || $_POST['ore'] != "" || $_POST['ore'] != " "){
	$data = "";
	
	$strength = "0";
	$dexterity = "0";
	$endurance = "0";
	$concentration = "0";
	$intelligence = "0";
	$levelreq = "0";
	$findForgeItems = $conn->prepare("SELECT * FROM forge WHERE username=?");
	$findForgeItems->bind_param("s", $char['username']);
	$findForgeItems->execute();
	$findForgeItemsResult = $findForgeItems->get_result();
	if($findForgeItemsResult->num_rows == "4"){
		while($inventory = $findForgeItemsResult->fetch_array()){
			$strength += $inventory['strength'];
			$dexterity += $inventory['dexterity'];
			$endurance += $inventory['endurance'];
			$concentration += $inventory['concentration'];
			$intelligence += $inventory['intelligence'];
			$levelreq += $inventory['levelreq'];
			$value += $inventory['value'];
			$itemtype = $inventory['type'];
			$power = $inventory['power'];
		}
			if($_POST['ore'] == "copper"){
				$dividend = 3;
			}elseif($_POST['ore'] == "iron"){
				$dividend = 2.5;
			}elseif($_POST['ore'] == "steel"){
				$dividend = 2;
			}else{
				print"alert('Your done.');";
				die();
			}
			$strength /= $dividend;
			$dexterity /= $dividend;
			$endurance /= $dividend;
			$concentration /= $dividend;
			$intelligence /= $dividend;
			$levelreq /= 4;
			$value /= $dividend;
			$itemname = "(F)".$char['username']." ".$itemtype;
			
			if($_POST['ore'] == "copper" && $char['copperore'] >= 50){
				$updateResources = $conn->prepare("UPDATE characters SET copperore=copperore-'50' WHERE username=?");
				$updateResources->bind_param("s", $char['username']);
				$updateResources->execute();
			}elseif($_POST['ore'] == "iron" && $char['copperore'] >= 100 && $char['ironore'] >= 50){
				$updateResources = $conn->prepare("UPDATE characters SET copperore=copperore-'100', ironore=ironore-'50' WHERE username=?");
				$updateResources->bind_param("s", $char['username']);
				$updateResources->execute();
			}elseif($_POST['ore'] == "steel" && $char['copperore'] >= 150 && $char['ironore'] >= 100 && $char['steelore'] >= 50){
				$updateResources = $conn->prepare("UPDATE characters SET copperore=copperore-'150', ironore=ironore-'100', steelore=steelore-'50' WHERE username=?");
				$updateResources->bind_param("s", $char['username']);
				$updateResources->execute();
			}else{
				print"alert('Not enough resources.');";
				die();
			}

				$addToInventory = $conn->prepare("INSERT INTO `inventory` (`username`, `itemname`, `equipped`, `levelreq`, `type`, `power`, `strength`, `dexterity`, `endurance`, `intelligence`, `concentration`, `value`) VALUES (?, ?, 'No', ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$addToInventory->bind_param("ssisiiiiiii", $char['username'], $itemname, $levelreq, $itemtype, $power, $strength, $dexterity, $endurance, $intelligence, $concentration, $value);
				$addToInventory->execute();
	$removeFromInventory = $conn->prepare("DELETE FROM forge WHERE username=?");
	$removeFromInventory->bind_param("s", $char['username']);
	$removeFromInventory->execute();
				
				$data .= "<strong>".$itemname."</strong> was created giving you the following advantages; Str: ".floor($strength)." Dex: ".floor($dexterity)." End: ".floor($endurance)." Con: ".floor($concentration)." Int: ".floor($intelligence).", valuing at ".number_format($value)." gold, requiring a level of ".number_format($levelreq)." from the user.<br />";
			
	}else{
		$data .="Not enough items in the forge.";
	}
	
}else{
	$data = "No ore selected.";
}

print("fillDiv('displayArea','".$data."');");
include('updatestats.php');
?>