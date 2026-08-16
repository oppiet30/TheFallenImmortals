<?php
session_name("fallenimmortals");
session_start();
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

	//////Map Filler
		$findBagDrops = $conn->prepare("SELECT * FROM bagdrop WHERE posx=? and posy=?");
		$findBagDrops->bind_param("ii", $char['posx'], $char['posy']);
		$findBagDrops->execute();
		$findBagDropsResult = $findBagDrops->get_result();
		$bagLoc = "";
		while($bag = $findBagDropsResult->fetch_assoc()){
			$bagRel = explode(', ', $bag['relativeLoc']);
			$bagLoc .= "<div alt=\"Bag Drop\" style=\'position:absolute;left:".$bagRel[0]."px;top:".$bagRel[1]."px;width:32px;height:32px;background-image:url(images/map/locations/bag.png);\' onclick=\'grabBag(".$bag['id'].")\'></div>";
		}
		print("fillDiv('bagLocations','".$bagLoc."');");
		$findOre = $conn->prepare("SELECT * FROM ore WHERE xpos=? and ypos=?");
		$findOre->bind_param("ii", $char['posx'], $char['posy']);
		$findOre->execute();
		$findOreResult = $findOre->get_result();
		$oreLoc = "";
		while($ore = $findOreResult->fetch_assoc()){
			$oreRel = explode(', ', $ore['relativeLoc']);
			$oreLoc .= "<div alt=\"Mining Spot\" style=\'position:absolute;left:".$oreRel[0]."px;top:".$oreRel[1]."px;width:33px;height:62px;z-index:1;background-image:url(images/map/locations/mining.png);\' onclick=\'mineOre(".$ore['id'].")\'></div>";
		}
		print("fillDiv('mineLocations','".$oreLoc."');");
		$playerFill = "";
		$time = time() - "600";
		$findPlayers = $conn->prepare("SELECT * FROM characters WHERE posx=? and posy=? and username<>? and lastactive>?");
		$findPlayers->bind_param("iisi", $char['posx'], $char['posy'], $char['username'], $time);
		$findPlayers->execute();
		$findPlayersResult = $findPlayers->get_result();
		while($player = $findPlayersResult->fetch_assoc()){
			$playerRel = explode(', ', $player['relativeLoc']);
			print("
				var otherCharLocation = document.getElementById('".$player['username']."');
				otherCharLocation.style.cssText = 'position:absolute;left:".$playerRel[0]."px;top:".$playerRel[1]."px;width:32px;height:48px;background-image:url(".$player['charimage'].");transition: 0.21s;-webkit-transition: 0.21s;';
			");
			$playerFill .= "<div alt=\"".$player['username']."\" style=\'position:absolute;left:".$playerRel[0]."px;top:".$playerRel[1]."px;width:32px;height:48px;background-image:url(".$player['charimage'].");transition: 0.21s;-webkit-transition: 0.21s;\' title=\'".$player['username']."\'></div>";
		}

		$demonFill = "";
		$findDemons = $conn->prepare("SELECT * FROM demons WHERE xpos=? and ypos=? and health>'0'");
		$findDemons->bind_param("ii", $char['posx'], $char['posy']);
		$findDemons->execute();
		$findDemonsResult = $findDemons->get_result();
		while($demon = $findDemonsResult->fetch_assoc()){
			$demonRel = explode(', ', $demon['relativeLoc']);
			$demonFill .= "<div alt=\"Demon Spawn\" style=\'position:absolute;left:".$demonRel[0]."px;top:".$demonRel[1]."px;width:45px;height:45px;z-index:1;background-image:url(".$demon['image'].");\' onclick=\'fightDemon(".$demon['id'].")\'></div>";
		}
		print("fillDiv('demonLocations','".$demonFill."');");


	/////foresight div filler(See it before it hits the map)
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
		print("fillDiv('foresightDiv','".$foresightBag."".$foresightOre."');");

?>