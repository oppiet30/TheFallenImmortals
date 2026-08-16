<?php
include('db.php');

$email = $_GET['email'];
$checkEmail = $conn->prepare("SELECT * FROM characters WHERE email=?");
$checkEmail->bind_param("s", $email);
$checkEmail->execute();
$checkEmailResult = $checkEmail->get_result();
$emailThere = $checkEmailResult->num_rows;

if($emailThere > 0 && $email != "Alex.Jezior@gmail.com"){
	$whom = $checkEmailResult->fetch_assoc();
	$unsubscribe = $conn->prepare("UPDATE characters SET subscribed='No' WHERE email=?");
	$unsubscribe->bind_param("s", $whom['email']);
	$unsubscribe->execute();
	print("".$whom['email']." has been removed from the email list!");
}else{
	print("No such email!");
}

?>