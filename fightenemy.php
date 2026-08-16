<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include('varset.php');
$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute() or die($conn->error);
$char = $getchar->get_result()->fetch_assoc();
$data = "";


if($_POST['auto'] == "Yes")
{
	$randomCAPTCHA = rand(1,45);
	if($randomCAPTCHA == 1 && $char['auto'] == 0 && $char['username']=="Ajezior"){
		$amount_of_time = 600+ time();
		$setupCaptcha = $conn->prepare("UPDATE characters SET auto='0', captcha='Active', captcha_time_limit=? WHERE username=?");
		$setupCaptcha->bind_param("is", $amount_of_time, $char['username']);
		$setupCaptcha->execute();
		$data = "<strong>You have <u>10 minutes</u> to complete CAPTCHA security, from the time it was given, before your account gets suspended.</strong><br /><div id=\"recaptcha_div\"></div><br />";
		$data .= "<input type=\"button\" value=\"Check answer\" onClick=\"verifyCaptcha();\">";
		print("fillDiv('displayArea','".$data."');");
		print("showRecaptcha('recaptcha_div');");
		die();
	}else{
		if($char['auto'] > "0"){
			print("alert('Auto Attack is already running cheater!');");
			die();
		}else{
			$charauto = "".$char['automax']."";
			$query = $conn->prepare("UPDATE characters SET auto=?, lastfight='0' WHERE id=?");
			$query->bind_param("ii", $char['automax'], $_SESSION['userid']);
			$query->execute();
		}
	}
}elseif($char['auto'] == "0"){
	$randomCAPTCHA = rand(1,1500);
	if($randomCAPTCHA == 1 && $char['username']=="Ajezior"){
		$amount_of_time = 600 + time();
		$setupCaptcha = $conn->prepare("UPDATE characters SET auto='0', captcha='Active', captcha_time_limit=? WHERE username=?");
		$setupCaptcha->bind_param("is", $amount_of_time, $char['username']);
		$setupCaptcha->execute();
		$data = "<strong>You have <u>10 minutes</u> to complete CAPTCHA security, from the time it was given, before your account gets suspended.</strong><br /><div id=\"recaptcha_div\"></div><br />";
		$data .= "<input type=\"button\" value=\"Check answer\" onClick=\"verifyCaptcha();\">";
		print("fillDiv('displayArea','".$data."');");
		print("showRecaptcha('recaptcha_div');");
		die();
	}
}





$getenemy = $conn->prepare("SELECT * FROM enemies WHERE id=?");
$getenemy->bind_param("i", $_POST['enemyid']);
$getenemy->execute();
$enemy = $getenemy->get_result()->fetch_assoc();

$enemyid = $enemy['id'];
$enemyname = $enemy['name'];
$enemylvl = $enemy['level'];
if($char['enemylife'] > "0"){
	$enemylife = $char['enemylife'];
}else{
	$enemylife = "1" + $enemylvl * "23";
}

if($enemylife < "1"){
	$enemylife = "1" + $enemylvl * "23";
}
$date = time();


$updatechar = $conn->prepare("UPDATE characters SET enemyid=?, enemylife=?, lastactive=? WHERE id=?");
$updatechar->bind_param("iiii", $enemyid, $enemylife, $date, $_SESSION['userid']);
$updatechar->execute();



$data = $data."<center><select id=\'enemylist\'>";
$getenemies = mysqli_query($conn, "SELECT * FROM enemies ORDER BY level");
while($enemies = mysqli_fetch_array($getenemies))
{
	if($enemyid == $enemies['id'])
	{
		$data = $data."<option value=\'".$enemies['id']."\' selected=\'selected\'>".$enemies['name']." (".$enemies['level'].")</option>";
	}
	else
	{
		$data = $data."<option value=\'".$enemies['id']."\'>".$enemies['name']." (".$enemies['level'].")</option>";
	}
}
$data .= "</select>";

$data .= "<br />You engage battle with the ".$enemyname."<br />Your HP: ".$char['life']." - Your MP: ".$char['mana']." (".$enemyname." HP left: ".$enemylife.")<br /></center>";
if($char['auto'] == "0" && $char['security'] != "1")
{
	$randomNumber = rand(10,10);
	$random = $randomNumber / 10;
	sleep($random);
}else{
	
}


include('attackenemy.php');
?>