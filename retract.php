<?php 
session_name("icsession");
session_start();
include('db.php');

$findapplication = mysqli_query("SELECT * FROM applications WHERE username='".$char['username']."'");
if(mysqli_num_rows($findapplication) == 1){

	$removeApplication = mysqli_query("DELETE FROM applications WHERE username='".$char['username']."'")or die(mysqli_error());
	$data = "You can now apply to a different guild.";
	$giveTheGoldBack = mysqli_query("UPDATE characters SET gold=gold+'900000' WHERE username='".$char['username']."'");

}else{
	
	$data = "Failed!";

}

print("fillDiv('displayArea','".$data."');");
include('updatestats.php');
?>