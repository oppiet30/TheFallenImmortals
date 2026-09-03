<?php
session_name("icsession");
session_start();
include('db.php');
$whom = ucwords(strtolower($_POST['whom']));
$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);
$data = "";

if(isset($_POST['adventureid'])){
	$findAdventureInQuestion = db_query("SELECT * FROM scavenger WHERE id=? AND username=?", [$_POST['adventureid'], $char['username']]);
	$adventure = db_fetch_assoc($findAdventureInQuestion);
		if($char['username'] == $adventure['username']){
			
			db_query("DELETE FROM scavenger WHERE id=?", [$adventure['id']]);

		}else{
			$data .= "This is not your adventure.<br />";
		}
}else{
	$data .= "Unable to find this fake adventure your have created.";
}

print("fillDiv('displayArea','".$data."');");
include('updatestats.php');
?>