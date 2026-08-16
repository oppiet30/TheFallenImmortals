<?php 
session_name("fallenimmortals");
session_start();
include('db.php');

$findapplication = $conn->prepare("SELECT * FROM applications WHERE username=?");
$findapplication->bind_param("s", $char['username']);
$findapplication->execute();
if($findapplication->get_result()->num_rows == 1){

	$removeApplication = $conn->prepare("DELETE FROM applications WHERE username=?");
	$removeApplication->bind_param("s", $char['username']);
	$removeApplication->execute() or die($conn->error);
	$data = "You can now apply to a different guild.";
	$giveTheGoldBack = $conn->prepare("UPDATE characters SET gold=gold+'900000' WHERE username=?");
	$giveTheGoldBack->bind_param("s", $char['username']);
	$giveTheGoldBack->execute();

}else{
	
	$data = "Failed!";

}

print("fillDiv('displayArea','".$data."');");
include('updatestats.php');
?>