<?php
session_name("fallenimmortals");
session_start();
include('db.php');
$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute() or die($conn->error);
$char = $getchar->get_result()->fetch_assoc();
$display = "<strong><a href=\"javascript: closeSecondPage();\">Close</a> | <a href=\"javascript: viewAccount();\">Back</a></strong><br /><br />";

if($_POST['oemail'] != NULL && $_POST['nemail'] != Null && $_POST['nemail2'] != Null){
	$oemail = $_POST['oemail'];
	$nemail = $_POST['nemail'];
	$nemail2 = $_POST['nemail2'];
	
	$checkNewEmail = $conn->prepare("SELECT * FROM characters WHERE email=?");
	$checkNewEmail->bind_param("s", $nemail);
	$checkNewEmail->execute();
	$CheckRowOnEmail = $checkNewEmail->get_result()->num_rows;
	
	if($oemail != $char['email']){
		$display .= "<center>Your current email address is not correct!</center><br />";
	}elseif($nemail != $nemail2){
		$display .= "<center>New email fields do not match!</center><br />";
	}elseif($nemail == $oemail){
		$display .= "<center>You old email and your new email are exactly the same! Try something different!</center><br />";
	}elseif($CheckRowOnEmail > "0"){
		$display .= "<center>Already an account signed up with that new email.</center><br />";
	}else{
		$randComfCode = md5(rand(1,100000000000));
		$to = $char['email'];
		$subject = "Email Change at The Fallen Immortals";
		$message = "Hello ".$char['username'].", \n \n You have requested to change your email at www.TheFallenImmortals.com. If you have not authorized this then ignore this email and change your password under the Edit Account link. \n If you have requested this change please follow this link: http://www.thefallenimmortals.com/activateemail.php?nemail=".$nemail."&&activationcode=".$randComfCode."";
		$from = "DONOTREPLY@TheFallenImmortals.com";
		$headers = "From:" . $from;
		mail($to,$subject,$message,$headers);
		$display .= "An activation was sent to your old email. Once you have followed the link in your OLD EMAIL address you new email will be updated to your account. Remember to check your spam/junk when looking for this email.<br /><br />";
		
		$addActivationDatabase = $conn->prepare("INSERT INTO activatenewemail (`username`, `newemail`, `verificationcode`) VALUES (?, ?, ?)");
		$addActivationDatabase->bind_param("sss", $char['username'], $nemail, $randComfCode);
		$addActivationDatabase->execute() or die("alert('Could not add verify code to database. Tell the admin!');");
	}
}elseif($_POST['opass'] != NULL && $_POST['npass'] != Null && $_POST['npass2'] != Null){
	// Legacy hash format, kept only to verify passwords that predate
	// the switch to password_hash()/PASSWORD_DEFAULT (bcrypt). Never
	// use murder() to create a new hash - only to check an old one.
	function murder($data){
	$salt = "'/0U'LL |\|3\/3R Ph19UR3 0U7 \/\/|-|@ 7|-|3 54L7 15. pLU5 \/\/|-|3R35 7|-|3 p3PP3R?";
	$salt = md5($salt);
	$data = md5($salt.$data);
	$data = base64_encode($data);
	$data = sha1($data);
	return $data;
	}

	$opassPlain = $_POST['opass'];
	$npassPlain = $_POST['npass'];
	$npass2Plain = $_POST['npass2'];

	$opassMatches = password_verify($opassPlain, $char['password']) || murder($opassPlain) === $char['password'];
	$npassSameAsOld = password_verify($npassPlain, $char['password']) || murder($npassPlain) === $char['password'];

	if(!$opassMatches){
		$display .= "<center>Your current password is incorrect.</center><br />";
	}elseif($npassSameAsOld){
		$display .= "<center>Your new password appears to be the same as your old one. Try something different.</center><br />";
	}elseif($npassPlain !== $npass2Plain){
		$display .= "<center>New passwords do not match!</center><br />";
	}else{
		$npass = password_hash($npassPlain, PASSWORD_DEFAULT);
		$randComfCode = md5(rand(1,100000000000));
		$to = $char['email'];
		$subject = "Password Change at The Fallen Immortals";
		$message = "Hello ".$char['username'].", \n \n You have requested to change your password at www.TheFallenImmortals.com. If you have not authorized this then ignore this email and change your password under the Edit Account link. \n If you have requested this change please follow this link: http://www.thefallenimmortals.com/activatepass.php?username=".$char['username']."&&activationcode=".$randComfCode."";
		$from = "DONOTREPLY@TheFallenImmortals.com";
		$headers = "From:" . $from;
		mail($to,$subject,$message,$headers);
		$display .= "An activation was sent to your email. Once you have followed the link in your email address you new password will be updated to your account. Remember to check your spam/junk when looking for this email.<br /><br />";
		
		$addActivationDatabase = $conn->prepare("INSERT INTO activatenewpassword (`username`, `newpassword`, `verificationcode`) VALUES (?, ?, ?)");
		$addActivationDatabase->bind_param("sss", $char['username'], $npass, $randComfCode);
		$addActivationDatabase->execute() or die("alert('Could not add verify code to database. Tell the admin!');");
	}

}elseif($_POST['newcolor'] != Null && $char['networth'] >= "5"){
	$newcolor = $_POST['newcolor'];
	if($newcolor == "1"){
		$color = "999999";
	}elseif($newcolor == "2"){
		$color = "555555";
	}elseif($newcolor == "3"){
		$color = "000099";
	}elseif($newcolor == "4"){
		$color = "3300FF";
	}elseif($newcolor == "5"){
		$color = "00FFFF";
	}elseif($newcolor == "6"){
		$color = "F535AA";
	}elseif($newcolor == "7"){
		$color = "6A287E";
	}elseif($newcolor == "8"){
		$color = "B93B8F";
	}elseif($newcolor == "9"){
		$color = "348017";
	}elseif($newcolor == "10"){
		$color = "41A317";
	}elseif($newcolor == "11"){
		$color = "8BB381";
	}elseif($newcolor == "12"){
		$color = "B1FB17";
	}elseif($newcolor == "13"){
		$color = "00AAFF";
	}else{
		die("alert(\'NO Chat COLOR!\');");
	}
	$updateRainbow = $conn->prepare("UPDATE characters SET chatcolour=? WHERE id=?");
	$updateRainbow->bind_param("si", $color, $_SESSION['userid']);
	$updateRainbow->execute();
	$display .= "<font color=\'".$color."\'>Chat color has been changed.</font><br /><br />";
}
$findReferals = $conn->prepare("SELECT * FROM characters WHERE refferal=?");
$findReferals->bind_param("s", $char['username']);
$findReferals->execute();
$numOfRef = $findReferals->get_result()->num_rows;
$display .= "<center><bold>Edit Account Information</bold></center></br >";
$display .= "<center>Refer friends: http://fallenimmortals.old/index.php?comrade=".$char['username']."</center>";
$display .= "<center>Number of your Referrals: ".$numOfRef."</center>";
$display .= "<table border=\'0px\'><tr><td>";

$display .= "<table style=\'border:0px;\'>";
$display .= "<tr><td colspan=\'2\'><center><b>Edit Email</b></center></td></tr>";
$display .= "<tr><td>Current Email:</td><td><input type=\'text\' id=\'oemail\'></td></tr>";
$display .= "<tr><td>New Email:</td><td><input type=\'text\' id=\'nemail\'></td></tr>";
$display .= "<tr><td>New Email Again:</td><td><input type=\'text\' id=\'nemail2\'></td></tr>";
$display .= "<tr><td colspan=\'2\' style=\'align: right;\'><input type=\'button\' value=\'Change\' onclick=\'changeEmail();\'></td></tr>";
$display .= "</table>";

$display .= "</td><td>";

$display .= "<table style=\'border:0px;\'>";
$display .= "<tr><td colspan=\'2\'><center><b>Edit Password</b></center></td></tr>";
$display .= "<tr><td>Current Password:</td><td><input type=\'text\' id=\'opass\'></td></tr>";
$display .= "<tr><td>New Password:</td><td><input type=\'text\' id=\'npass\'></td></tr>";
$display .= "<tr><td>New Password Again:</td><td><input type=\'text\' id=\'npass2\'></td></tr>";
$display .= "<tr><td colspan=\'2\'><input style=\'align: right;\' type=\'button\' value=\'Change\' onclick=\'changePassword();\'></td></tr>";
$display .= "</table>";

$display .= "</td></tr></table>";


$display .= "Edit Chat Color(Must have at least 5 Networth): ";
$display .= "<select id=\'newcolor\'>";
$display .= "<option style=\'color:#999999;\' value=\'1\'>Color 1</option>";
$display .= "<option style=\'color:#555555;\' value=\'2\'>Color 2</option>";
$display .= "<option style=\'color:#000099;\' value=\'3\'>Color 3</option>";
$display .= "<option style=\'color:#3300FF;\' value=\'4\'>Color 4</option>";
$display .= "<option style=\'color:#00FFFF;\' value=\'5\'>Color 5</option>";
$display .= "<option style=\'color:#F535AA;\' value=\'6\'>Color 6</option>";
$display .= "<option style=\'color:#6A287E;\' value=\'7\'>Color 7</option>";
$display .= "<option style=\'color:#B93B8F;\' value=\'8\'>Color 8</option>";
$display .= "<option style=\'color:#348017;\' value=\'9\'>Color 9</option>";
$display .= "<option style=\'color:#41A317;\' value=\'10\'>Color 10</option>";
$display .= "<option style=\'color:#8BB381;\' value=\'11\'>Color 11</option>";
$display .= "<option style=\'color:#B1FB17;\' value=\'12\'>Color 12</option>";
$display .= "<option style=\'color:#00AAFF;\' value=\'13\'>Color 13</option>";
$display .= "</select>";
if($char['networth'] >= "5"){
	$display .= "<input type=\'button\' value=\'Taste teh Rainbow\' onclick=\'runRainbow();\' />";
}

print("fillDiv('secondPage','".$display."');");
include("updatestats.php");
?>