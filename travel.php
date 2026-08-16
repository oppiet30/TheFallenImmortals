<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_name("fallenimmortals");
	session_start();
}
include('db.php');


$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute() or die($conn->error);
$char = $getchar->get_result()->fetch_assoc();
$charrel = explode(", ", $char['relativeLoc']);

$findMap = $conn->prepare("SELECT * FROM map WHERE xpos=? and ypos=?");
$findMap->bind_param("ii", $char['posx'], $char['posy']);
$findMap->execute();
$map = $findMap->get_result()->fetch_assoc();
		//Show the teleporter if purchased
		$xtop = $char['posx'] + $char['foresight'];
		$xbottom = $char['posx'] - $char['foresight'];
		$ytop = $char['posy'] + $char['foresight'];
		$ybottom = $char['posy'] - $char['foresight'];
		$grabBag = $conn->prepare("SELECT * FROM `bagdrop` WHERE (`posx` BETWEEN ? AND ?) AND (`posy` BETWEEN ? AND ?)");
		$grabBag->bind_param("iiii", $xbottom, $xtop, $ybottom, $ytop);
		$grabBag->execute();
		$grabBagResult = $grabBag->get_result();
		$bag = $grabBagResult->fetch_assoc();
		$there = $grabBagResult->num_rows;
		if($there > "0"){
			$foresightBag = "-There is a bag at ".$bag['posx'].", ".$bag['posy']."<br />";
		}
		$findOre = $conn->prepare("SELECT * FROM ore WHERE (`xpos` BETWEEN ? AND ?) AND (`ypos` BETWEEN ? AND ?)");
		$findOre->bind_param("iiii", $xbottom, $xtop, $ybottom, $ytop);
		$findOre->execute();
		$findOreResult = $findOre->get_result();
		$there = $findOreResult->num_rows;
		if($there > "0"){
			$ore = $findOreResult->fetch_assoc();
			$foresightOre = "-An Ore was spotted at ".$ore['xpos'].",".$ore['ypos']."<br />";
		}
$data = "<div id=\"MainCanvas\" oncontextmenu=\"rightClickList(event);return false;\" style=\"position:relative; top:110px; width:1050px; height:550px; background-color:#000000; background-image:url(".$map['background'].");\">";
	$data .= "<div id=\'mineLocations\'>";
	$findOre = $conn->prepare("SELECT * FROM ore WHERE xpos=? and ypos=?");
	$findOre->bind_param("ii", $char['posx'], $char['posy']);
	$findOre->execute();
	$findOreResult = $findOre->get_result();
	while($ore = $findOreResult->fetch_assoc()){
		$oreRel = explode(', ', $ore['relativeLoc']);
		$data .= "<div alt=\"Mining Ore\" style=\'position:absolute;left:".$oreRel[0]."px;top:".$oreRel[1]."px;width:33px;height:62px;z-index:1;background-image:url(images/map/locations/mining.png);\' onclick=\'mineOre(".$ore['id'].")\'></div>";
	}
	$data .= "</div>";

	$data .= "<div id=\'bagLocations\'>";
	$findBagDrops = $conn->prepare("SELECT * FROM bagdrop WHERE posx=? and posy=?");
	$findBagDrops->bind_param("ii", $char['posx'], $char['posy']);
	$findBagDrops->execute();
	$findBagDropsResult = $findBagDrops->get_result();
	while($bag = $findBagDropsResult->fetch_assoc()){
		$bagRel = explode(', ', $bag['relativeLoc']);
		$data .= "<div alt=\"Bag Drop\" style=\'position:absolute;left:".$bagRel[0]."px;top:".$bagRel[1]."px;width:32px;height:32px;background-image:url(images/map/locations/bag.png);\' onclick=\'grabBag(".$bag['id'].")\'></div>";
	}
	$data .= "</div>";

	$data .= "<div id=\'demonLocations\'>";
	$findDemons = $conn->prepare("SELECT * FROM demons WHERE xpos=? and ypos=? and health>'0'");
	$findDemons->bind_param("ii", $char['posx'], $char['posy']);
	$findDemons->execute();
	$findDemonsResult = $findDemons->get_result();
	while($demon = $findDemonsResult->fetch_assoc()){
		$demonRel = explode(', ', $demon['relativeLoc']);
		$data .= "<div alt=\"Demon Spawn\" style=\'position:absolute;left:".$demonRel[0]."px;top:".$demonRel[1]."px;width:45px;height:45px;z-index:1;background-image:url(".$demon['image'].");\' onclick=\'fightDemon(".$demon['id'].")\'></div>";
	}
	$data .= "</div>";

	$data .= "<div id=\'otherPlayers\'>";
	$time = time() - "600";
	$findPlayers = $conn->prepare("SELECT * FROM characters WHERE posx=? and posy=? and username<>? and lastactive>?");
	$findPlayers->bind_param("iisi", $char['posx'], $char['posy'], $char['username'], $time);
	$findPlayers->execute();
	$findPlayersResult = $findPlayers->get_result();
	while($player = $findPlayersResult->fetch_assoc()){
		$playerRel = explode(', ', $player['relativeLoc']);
		$data .= "<div id=\"".$player['username']."\" style=\'position:absolute;left:".$playerRel[0]."px;top:".$playerRel[1]."px;width:32px;height:48px;background-image:url(".$player['charimage'].");\' title=\'".$player['username']."\'><div id=\'otherCharHair\' style=\'position:absolute;width:32px;height:48px;background-image:url(".$player['charhair'].");\'><div id=\'otherCharLeggings\' style=\'position:absolute;width:32px;height:48px;background-image:url(".$player['charleggings'].");\'></div></div></div>";
	}
	$data .= "</div>";

	$data .= "<div style=\'position:relative;left:".$charrel[0]."px;top:".$charrel[1]."px;width:32px;height:48px;background-image:url(".$char['charimage'].");\' id=\"charLocation\"><div id=\'charDiv\'><div id=\'charHair\' style=\'position:absolute;width:32px;height:48px;background-image:url(".$char['charhair'].");\'><div id=\'charLeggings\' style=\'position:absolute;width:32px;height:48px;background-image:url(".$char['charleggings'].");\'></div></div></div></div>";

	//Loading the map background image and loading the right click event area. Hidden until right click executed.
	$data .= "<div id=\'rightClickList\' style=\'display: none;\'><a>Add Structure</a><br /><a>Add NPC</a><br /><a>Add Monster</a><br /><a onclick=\'closeRightClickList();\'>Cancel</a></div>";
	
	//Display character location in the corner
	$data .= "<div id=\"dispLocation\" style=\"position:absolute; top:0px; left:0px; font-size:10px;\">Location: (".$char['posx'].", ".$char['posy'].")</div>";


$data .= "</div>";


print("fillDiv('2dCanvas','".$data."');");
//require_once("travelOtherPlayers.php");
print("loadMap();");
?>