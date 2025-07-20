<?php
include('db.php');

$getUserForChange = mysqli_query($conn, "SELECT * FROM activatenewemail WHERE newemail='".$_GET['nemail']."' AND verificationcode='".$_GET['activationcode']."'");
$getCodeNumRows = mysql_num_rows($getUserForChange);
if($getCodeNumRows > "0"){
	
	$verify = mysql_fetch_assoc($getUserForChange);
	$checkIfInUse = mysqli_query($conn, "SELECT * FROM characters WHERE email='".$verify['newemail']."'");
	if(mysql_num_rows($checkIfInUse) > "0"){
		print "Someone is already using this email address!";
	}else{
		print "You have changed you email to ".$verify['newemail']."";
		$updateEmail = mysqli_query($conn, "UPDATE characters SET email='".$verify['newemail']."' WHERE username='".$verify['username']."'");
		$deleteVerifyCode = mysqli_query($conn, "DELETE FROM activatenewemail WHERE id='".$verify['id']."'");
	}
	
}else{
	print "Follow the activation link from your Old Email address!";
}
?>