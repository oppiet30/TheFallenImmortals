<?php 
session_name("icsession");
session_start();
include('db.php');

if($_POST['itemid'] != NULL || $_POST['itemid'] != "" || $_POST['itemid'] != " "){
	
	$data = "";
	$querty = db_query("SELECT * FROM inventory WHERE id=? AND username=?", [$_POST['itemid'], $char['username']]);
	if(db_num_rows($querty) != 1){
		print("alert('This is not your item!');");
		die();
	}
	$inventory = db_fetch_assoc($querty);
	$squerty = db_query("SELECT * FROM forge WHERE username=?", [$char['username']]);
	if(db_num_rows($squerty) >= 4){
		print("alert('You can only Forge four items at a time.');");
		die();
	}
	if(db_num_rows($squerty) > 0){
		$forge = db_fetch_assoc($squerty);
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
	$addToForge = db_query("INSERT INTO `forge` (`username`, `itemname`, `equipped`, `levelreq`, `type`, `power`, `strength`, `dexterity`, `endurance`, `intelligence`, `concentration`, `value`) VALUES (?, ?, 'No', ?, ?, ?, ?, ?, ?, ?, ?, ?)", [$inventory['username'], $inventory['itemname'], $inventory['levelreq'], $inventory['type'], $inventory['power'], $inventory['strength'], $inventory['dexterity'], $inventory['endurance'], $inventory['intelligence'], $inventory['concentration'], $inventory['value']]);
	$removeFromInventory = db_query("DELETE FROM inventory WHERE id=?", [$inventory['id']]);
	
	
}else{
	$data = "No item selected.";
}
print("fillDiv('displayArea','".$data."');");
include("newshop.php");
?>