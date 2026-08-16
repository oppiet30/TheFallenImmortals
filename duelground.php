<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_name("fallenimmortals");
	session_start();
}
include('db.php');
$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute();
$char = $getchar->get_result()->fetch_assoc();
$data = "";

if($char['life'] > 0){
	if($char['posx'] == "25" && $char['posy'] == "25"){
		$data .= "You are in the Duel Ground! A field full of blood and guts... choose your opponent wisely.<br /><br />";
		$time = time() - "600";
		$findCharacters = $conn->prepare("SELECT * FROM characters where level>'100' AND lastactive>? AND username<>? ORDER BY level");
		$findCharacters->bind_param("is", $time, $char['username']);
		$findCharacters->execute();
		$findCharactersResult = $findCharacters->get_result();
		$data .= "<table>";
		$data .= "<tr><td>Username</td><td colspan=\'2\'>Level</td></tr>";
		while($duel = $findCharactersResult->fetch_array()){
			$data .= "<tr><td>".$duel['username']."</td><td>".number_format($duel['level'])."</td><td><a href=\'javascript: requestFight(\"".$duel['username']."\");\'>Request Fight</a></td></tr>";
		}
		$data .= "</table>";
	}else{
		$data .= "Would you like to go to the Duel Ground(Location: 25, 25)? <a href=\'javascript: goDuelGround(\"Yes\");\'>Yes</a>";
	}
}else{
	$data .= "Dead people cannot fight.";
}
print("fillDiv('displayArea','".$data."');");
include('active.php');
?>