<?php
include('db.php');

$getUserForChange = $conn->prepare("SELECT * FROM activatenewpassword WHERE username=? AND verificationcode=?");
$getUserForChange->bind_param("ss", $_GET['username'], $_GET['activationcode']);
$getUserForChange->execute();
$getUserForChangeResult = $getUserForChange->get_result();
$getCodeNumRows = $getUserForChangeResult->num_rows;
if($getCodeNumRows > "0"){

	$verify = $getUserForChangeResult->fetch_assoc();
	$checkIfInUse = $conn->prepare("SELECT * FROM characters WHERE username=?");
	$checkIfInUse->bind_param("s", $verify['username']);
	$checkIfInUse->execute();
		print "You have changed your password!";
		$updateEmail = $conn->prepare("UPDATE characters SET password=? WHERE username=?");
		$updateEmail->bind_param("ss", $verify['newpassword'], $verify['username']);
		$updateEmail->execute();
		$deleteVerifyCode = $conn->prepare("DELETE FROM activatenewpassword WHERE id=?");
		$deleteVerifyCode->bind_param("i", $verify['id']);
		$deleteVerifyCode->execute();
	
}else{
	print "Follow the activation link from your Email address!";
}
?>