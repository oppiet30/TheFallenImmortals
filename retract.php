<?php 
session_name("icsession");
session_start();
include('db.php');

$findapplication = db_query("SELECT * FROM applications WHERE username=?", [$char['username']]);
if(db_num_rows($findapplication) == 1){

	$removeApplication = db_query("DELETE FROM applications WHERE username=?", [$char['username']]);
	$data = "You can now apply to a different guild.";
	$giveTheGoldBack = db_query("UPDATE characters SET gold=gold+'900000' WHERE username=?", [$char['username']]);

}else{
	
	$data = "Failed!";

}

print("fillDiv('displayArea','".$data."');");
include('updatestats.php');
?>