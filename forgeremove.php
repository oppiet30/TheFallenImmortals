<?php 
session_name("icsession");
session_start();
include('db.php');

if($_POST['itemid'] != NULL || $_POST['itemid'] != "" || $_POST['itemid'] != " "){
	
	$data = "";
	$querty = mysqli_query($conn, "SELECT * FROM forge WHERE id='".$_POST['itemid']."' AND username='".$char['username']."'");
	if(mysqli_num_rows($querty) != 1){
		print("alert('This is not your item!');");
		die();
	}
	$inventory = mysqli_fetch_assoc($querty);

	$addToInventory = mysqli_query($conn, "INSERT INTO `inventory` (`username`, `itemname`, `equipped`, `levelreq`, `type`, `power`, `strength`, `dexterity`, `endurance`, `intelligence`, `concentration`, `value`) VALUES ('".$inventory['username']."', '".$inventory['itemname']."', 'No', '".$inventory['levelreq']."', '".$inventory['type']."', '".$inventory['power']."', '".$inventory['strength']."', '".$inventory['dexterity']."', '".$inventory['endurance']."', '".$inventory['intelligence']."', '".$inventory['concentration']."', '".$inventory['value']."')");
	$removeFromForge = mysqli_query($conn, "DELETE FROM forge WHERE id='".$inventory['id']."'");
	
	
}else{
	$data = "No item selected.";
}
print("fillDiv('displayArea','".$data."');");
include("newshop.php");
?>