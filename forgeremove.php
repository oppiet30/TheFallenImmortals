<?php 
session_name("fallenimmortals");
session_start();
include('db.php');

if($_POST['itemid'] != NULL || $_POST['itemid'] != "" || $_POST['itemid'] != " "){
	
	$data = "";
	$querty = $conn->prepare("SELECT * FROM forge WHERE id=? AND username=?");
	$querty->bind_param("is", $_POST['itemid'], $char['username']);
	$querty->execute();
	$quertyResult = $querty->get_result();
	if($quertyResult->num_rows != 1){
		print("alert('This is not your item!');");
		die();
	}
	$inventory = $quertyResult->fetch_assoc();

	$addToInventory = $conn->prepare("INSERT INTO `inventory` (`username`, `itemname`, `equipped`, `levelreq`, `type`, `power`, `strength`, `dexterity`, `endurance`, `intelligence`, `concentration`, `value`) VALUES (?, ?, 'No', ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	$addToInventory->bind_param("ssisiiiiiii", $inventory['username'], $inventory['itemname'], $inventory['levelreq'], $inventory['type'], $inventory['power'], $inventory['strength'], $inventory['dexterity'], $inventory['endurance'], $inventory['intelligence'], $inventory['concentration'], $inventory['value']);
	$addToInventory->execute();
	$removeFromForge = $conn->prepare("DELETE FROM forge WHERE id=?");
	$removeFromForge->bind_param("i", $inventory['id']);
	$removeFromForge->execute();
	
	
}else{
	$data = "No item selected.";
}
print("fillDiv('displayArea','".$data."');");
include("newshop.php");
?>