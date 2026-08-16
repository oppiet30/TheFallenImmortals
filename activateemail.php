<?php
include('db.php');

$getUserForChange = $conn->prepare("SELECT * FROM activatenewemail WHERE newemail=? AND verificationcode=?");
$getUserForChange->bind_param("ss", $_GET['nemail'], $_GET['activationcode']);
$getUserForChange->execute();
$getUserForChangeResult = $getUserForChange->get_result();
$getCodeNumRows = $getUserForChangeResult->num_rows;
if($getCodeNumRows > "0"){

	$verify = $getUserForChangeResult->fetch_assoc();
	$checkIfInUse = $conn->prepare("SELECT * FROM characters WHERE email=?");
	$checkIfInUse->bind_param("s", $verify['newemail']);
	$checkIfInUse->execute();
	if($checkIfInUse->get_result()->num_rows > "0"){
		print "Someone is already using this email address!";
	}else{
		print "You have changed you email to ".$verify['newemail']."";
		$updateEmail = $conn->prepare("UPDATE characters SET email=? WHERE username=?");
		$updateEmail->bind_param("ss", $verify['newemail'], $verify['username']);
		$updateEmail->execute();
		$deleteVerifyCode = $conn->prepare("DELETE FROM activatenewemail WHERE id=?");
		$deleteVerifyCode->bind_param("i", $verify['id']);
		$deleteVerifyCode->execute();
	}
	
}else{
	print "Follow the activation link from your Old Email address!";
}
?>