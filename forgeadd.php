<?php 
session_name("fallenimmortals");
session_start();
include('db.php');

if($_POST['itemid'] != NULL || $_POST['itemid'] != "" || $_POST['itemid'] != " "){
	
	$data = "";
	$querty = $conn->prepare("SELECT * FROM inventory WHERE id=? AND username=?");
	$querty->bind_param("is", $_POST['itemid'], $char['username']);
	$querty->execute();
	$quertyResult = $querty->get_result();
	if($quertyResult->num_rows != 1){
		print("alert('This is not your item!');");
		die();
	}
	$inventory = $quertyResult->fetch_assoc();
	$squerty = $conn->prepare("SELECT * FROM forge WHERE username=?");
	$squerty->bind_param("s", $char['username']);
	$squerty->execute();
	$squertyResult = $squerty->get_result();
	if($squertyResult->num_rows >= 4){
		print("alert('You can only Forge four items at a time.');");
		die();
	}
	if($squertyResult->num_rows > 0){
		$forge = $squertyResult->fetch_assoc();
		if($forge['type'] != $inventory['type']){
			print("alert('You can only add one item type at a time to forge.');");
			die();
		}
	}
	if($inventory['equipped'] == "Yes"){
		print("alert('Equipped items do not go into forge!');");
		die();
	}
	if(substr($inventory['itemname'], 0, 3) == "[F]"){
		print("alert('You cannot add items that have already been forged.');");
		die();
	}
	$addToForge = $conn->prepare("INSERT INTO `forge` (`username`, `itemname`, `equipped`, `levelreq`, `type`, `power`, `strength`, `dexterity`, `endurance`, `intelligence`, `concentration`, `value`) VALUES (?, ?, 'No', ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	$addToForge->bind_param("ssisiiiiiii", $inventory['username'], $inventory['itemname'], $inventory['levelreq'], $inventory['type'], $inventory['power'], $inventory['strength'], $inventory['dexterity'], $inventory['endurance'], $inventory['intelligence'], $inventory['concentration'], $inventory['value']);
	$addToForge->execute();
	$removeFromInventory = $conn->prepare("DELETE FROM inventory WHERE id=?");
	$removeFromInventory->bind_param("i", $inventory['id']);
	$removeFromInventory->execute();
	
	
}else{
	$data = "No item selected.";
}
print("fillDiv('displayArea','".$data."');");
include("newshop.php");
?>