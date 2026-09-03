<?php
include('db.php');

$email = $_GET['email'];
$checkEmail = db_query("SELECT * FROM characters WHERE email=?", [$email]);
$emailThere = db_num_rows($checkEmail);

if($emailThere > 0 && $email != "Alex.Jezior@gmail.com"){
	$whom = db_fetch_assoc($checkEmail);
	$unsubscribe = db_query("UPDATE characters SET subscribed='No' WHERE email=?", [$whom['email']]);
	print("".$whom['email']." has been removed from the email list!");
}else{
	print("No such email!");
}

?>