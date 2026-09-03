<?php
include('db.php');

$getUserForChange = db_query("SELECT * FROM activatenewemail WHERE newemail=? AND verificationcode=?", [$_GET['nemail'], $_GET['activationcode']]);
$getCodeNumRows = db_num_rows($getUserForChange);
if($getCodeNumRows > "0"){
	
	$verify = db_fetch_assoc($getUserForChange);
	$checkIfInUse = db_query("SELECT * FROM characters WHERE email=?", [$verify['newemail']]);
	if(db_num_rows($checkIfInUse) > "0"){
		print "Someone is already using this email address!";
	}else{
		print "You have changed you email to ".$verify['newemail']."";
		$updateEmail = db_query("UPDATE characters SET email=? WHERE username=?", [$verify['newemail'], $verify['username']]);
		$deleteVerifyCode = db_query("DELETE FROM activatenewemail WHERE id=?", [$verify['id']]);
	}
	
}else{
	print "Follow the activation link from your Old Email address!";
}
?>