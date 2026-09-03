<?php 
session_name("icsession");
session_start();
include('db.php');

if($_POST['itemid'] != NULL || $_POST['itemid'] != "" || $_POST['itemid'] != " "){
	
	$data = "";
	$querty = db_query("SELECT * FROM forge WHERE id=? AND username=?", [$_POST['itemid'], $char['username']]);
	if(db_num_rows($querty) != 1){
		print("alert('This is not your item!');");
		die();
	}
	$inventory = db_fetch_assoc($querty);

	$addToInventory = db_query("INSERT INTO `inventory` (`username`, `itemname`, `equipped`, `levelreq`, `type`, `power`, `strength`, `dexterity`, `endurance`, `intelligence`, `concentration`, `value`) VALUES (?, ?, 'No', ?, ?, ?, ?, ?, ?, ?, ?, ?)", [$inventory['username'], $inventory['itemname'], $inventory['levelreq'], $inventory['type'], $inventory['power'], $inventory['strength'], $inventory['dexterity'], $inventory['endurance'], $inventory['intelligence'], $inventory['concentration'], $inventory['value']]);
	$removeFromForge = db_query("DELETE FROM forge WHERE id=?", [$inventory['id']]);
	
	
}else{
	$data = "No item selected.";
}
print("fillDiv('displayArea','".$data."');");
include("newshop.php");
?>