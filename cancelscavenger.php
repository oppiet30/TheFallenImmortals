<?php
session_name("fallenimmortals");
session_start();
include('db.php');
$whom = ucwords(strtolower($_POST['whom']));
$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute() or die("Not logged in!");
$char = $getchar->get_result()->fetch_assoc();
$data = "";

if(isset($_POST['adventureid'])){
	$findAdventureInQuestion = $conn->prepare("SELECT * FROM scavenger WHERE id=? AND username=?");
	$findAdventureInQuestion->bind_param("is", $_POST['adventureid'], $char['username']);
	$findAdventureInQuestion->execute() or die("alert(\'Problem finding adventure!\');");
	$adventure = $findAdventureInQuestion->get_result()->fetch_assoc();
		if($char['username'] == $adventure['username']){

			$removeAdventure = $conn->prepare("DELETE FROM scavenger WHERE id=?");
			$removeAdventure->bind_param("i", $adventure['id']);
			$removeAdventure->execute();

		}else{
			$data .= "This is not your adventure.<br />";
		}
}else{
	$data .= "Unable to find this fake adventure your have created.";
}

print("fillDiv('displayArea','".$data."');");
include('updatestats.php');
?>