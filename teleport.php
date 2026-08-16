<?php
session_name("fallenimmortals");
session_start();
include('db.php');
$whom = ucwords(strtolower($_POST['whom']));
$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute();
$char = $getchar->get_result()->fetch_assoc();

if($char['teleporter'] == "Yes"){
	$xcord = $_POST['xcord'];
	$ycord = $_POST['ycord'];
	$lastTeleport = $char['teleportlast'] + 420;
	if($lastTeleport > time()){
		die("alert('You can only use the teleport once every 7 minutes.');");
	}
	if($xcord <= "100" && $xcord >= "1" && $ycord <= "100" && $ycord >= "1"){
		
		$timeTeleported = time();
		$updateLocation = $conn->prepare("UPDATE characters SET posx=?, posy=?, teleportlast=? WHERE username=?");
		$updateLocation->bind_param("iiis", $xcord, $ycord, $timeTeleported, $char['username']);
		$updateLocation->execute();
		$findMap = $conn->prepare("SELECT * FROM map WHERE xpos=? and ypos=?");
		$findMap->bind_param("ii", $xcord, $ycord);
		$findMap->execute();
		$map = $findMap->get_result()->fetch_assoc();
		print("fillDiv('locationMap','<div style=\'width:480px;height:480px;background-color:#000000;background-image:url(".$map['background'].");padding:".$map['locationpadding'].";\'></div>');");
		print("fillDiv('cLocation','Current Location: (".$char['posx'].", ".$char['posy'].")');");
		print("alert('You awaken in ".$xcord.", ".$ycord.". Did you even fall asleep?');");
		
	}else{
		print("alert('Your new location cannot be beyond 100, 100. And below 1, 1.');");
	}

}else{
	print("alert('You need to buy a teleporter from the cash page.');");
}
include('updatestats.php');
?>