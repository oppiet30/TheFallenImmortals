<?php
include('db.php');

$getUserForChange = mysqli_query($conn, "SELECT * FROM activatenewpassword WHERE username='".$_GET['username']."' AND verificationcode='".$_GET['activationcode']."'");
$getCodeNumRows = mysql_num_rows($getUserForChange);
if($getCodeNumRows > "0"){
	
	$verify = mysql_fetch_assoc($getUserForChange);
	$checkIfInUse = mysqli_query($conn, "SELECT * FROM characters WHERE username='".$verify['username']."'");
		print "You have changed your password!";
		$updateEmail = mysqli_query($conn, "UPDATE characters SET password='".$verify['newpassword']."' WHERE username='".$verify['username']."'");
		$deleteVerifyCode = mysqli_query($conn, "DELETE FROM activatenewpassword WHERE id='".$verify['id']."'");
	
}else{
	print "Follow the activation link from your Email address!";
}
?>