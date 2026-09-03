<?php
include('db.php');

$getUserForChange = db_query("SELECT * FROM activatenewpassword WHERE username=? AND verificationcode=?", [$_GET['username'], $_GET['activationcode']]);
$getCodeNumRows = db_num_rows($getUserForChange);
if($getCodeNumRows > "0"){
	
	$verify = db_fetch_assoc($getUserForChange);
	$checkIfInUse = db_query("SELECT * FROM characters WHERE username=?", [$verify['username']]);
		print "You have changed your password!";
		$updateEmail = db_query("UPDATE characters SET password=? WHERE username=?", [$verify['newpassword'], $verify['username']]);
		$deleteVerifyCode = db_query("DELETE FROM activatenewpassword WHERE id=?", [$verify['id']]);
	
}else{
	print "Follow the activation link from your Email address!";
}
?>