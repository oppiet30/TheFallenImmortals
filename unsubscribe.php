<?php
include('db.php');

$email = $_GET['email'];
$checkEmail = mysqli_query($conn, "SELECT * FROM characters WHERE email='".$email."'");
$emailThere = mysqli_num_rows($checkEmail);

if($emailThere > 0 && $email != "Alex.Jezior@gmail.com"){
	$whom = mysqli_fetch_assoc($checkEmail);
	$unsubscribe = mysqli_query($conn, "UPDATE characters SET subscribed='No' WHERE email='".$whom['email']."'");
	print("".$whom['email']." has been removed from the email list!");
}else{
	print("No such email!");
}

?>