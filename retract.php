<?php 
session_name("icsession");
session_start();
include('db.php');

$findapplication = mysqli_query($conn, "SELECT * FROM applications WHERE username='".$char['username']."'");
if(mysqli_num_rows($findapplication) == 1){

	$removeApplication = mysqli_query($conn, "DELETE FROM applications WHERE username='".$char['username']."'")or die(mysql_error());
	$data = "You can now apply to a different guild.";
	$giveTheGoldBack = mysqli_query($conn, "UPDATE characters SET gold=gold+'900000' WHERE username='".$char['username']."'");

}else{
	
	$data = "Failed!";

}

print("fillDiv('displayArea','".$data."');");
include('updatestats.php');
?>