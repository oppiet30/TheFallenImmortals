<?php
session_name("fallenimmortals");
session_start();
include('db.php');
$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);
$charrel = explode(", ", $char['relativeLoc']);

$findMap = db_query("SELECT * FROM map WHERE xpos=? and ypos=?", [$char['posx'], $char['posy']]);
$map = db_fetch_assoc($findMap);

	//////Map Filler
		$findBagDrops = db_query("SELECT * FROM bagdrop WHERE posx=? and posy=?", [$char['posx'], $char['posy']]);
		$bagLoc = "";
		while($bag = db_fetch_assoc($findBagDrops)){
			$bagRel = explode(', ', $bag['relativeLoc']);
			$bagLoc .= "<div alt=\"Bag Drop\" style=\'position:absolute;left:".$bagRel[0]."px;top:".$bagRel[1]."px;width:32px;height:32px;background-image:url(images/map/locations/bag.png);\' onclick=\'grabBag(".$bag['id'].")\'></div>";
		}
		print("fillDiv('bagLocations','".$bagLoc."');");
		$findOre = db_query("SELECT * FROM ore WHERE xpos=? and ypos=?", [$char['posx'], $char['posy']]);
		$oreLoc = "";
		while($ore = db_fetch_assoc($findOre)){
			$oreRel = explode(', ', $ore['relativeLoc']);
			$oreLoc .= "<div alt=\"Mining Spot\" style=\'position:absolute;left:".$oreRel[0]."px;top:".$oreRel[1]."px;width:33px;height:62px;z-index:1;background-image:url(images/map/locations/mining.png);\' onclick=\'mineOre(".$ore['id'].")\'></div>";
		}
		print("fillDiv('mineLocations','".$oreLoc."');");
		$playerFill = "";
		$time = time() - "600";
		$findPlayers = db_query("SELECT * FROM characters WHERE posx=? and posy=? and username<>? and lastactive>?", [$char['posx'], $char['posy'], $char['username'], $time]);
		while($player = db_fetch_assoc($findPlayers)){
			$playerRel = explode(', ', $player['relativeLoc']);
			print("
				var otherCharLocation = document.getElementById('".$player['username']."');
				otherCharLocation.style.cssText = 'position:absolute;left:".$playerRel[0]."px;top:".$playerRel[1]."px;width:32px;height:48px;background-image:url(".$player['charimage'].");transition: 0.21s;-webkit-transition: 0.21s;';
			");
			$playerFill .= "<div alt=\"".$player['username']."\" style=\'position:absolute;left:".$playerRel[0]."px;top:".$playerRel[1]."px;width:32px;height:48px;background-image:url(".$player['charimage'].");transition: 0.21s;-webkit-transition: 0.21s;\' title=\'".$player['username']."\'></div>";
		}
		
		$demonFill = "";
		$findDemons = db_query("SELECT * FROM demons WHERE xpos=? and ypos=? and health>'0'", [$char['posx'], $char['posy']]);
		while($demon = db_fetch_assoc($findDemons)){
			$demonRel = explode(', ', $demon['relativeLoc']);
			$demonFill .= "<div alt=\"Demon Spawn\" style=\'position:absolute;left:".$demonRel[0]."px;top:".$demonRel[1]."px;width:45px;height:45px;z-index:1;background-image:url(".$demon['image'].");\' onclick=\'fightDemon(".$demon['id'].")\'></div>";
		}
		print("fillDiv('demonLocations','".$demonFill."');");
		
		
	/////foresight div filler(See it before it hits the map)
		$xtop = $char['posx'] + $char['foresight'];
		$xbottom = $char['posx'] - $char['foresight'];
		$ytop = $char['posy'] + $char['foresight'];
		$ybottom = $char['posy'] - $char['foresight'];
		$grabBag = db_query("SELECT * FROM `bagdrop` WHERE (`posx` BETWEEN ? AND ?) AND (`posy` BETWEEN ? AND ?)", [$xbottom, $xtop, $ybottom, $ytop]);
		$bag = db_fetch_assoc($grabBag);
		$there = db_num_rows($grabBag);
		if($there > "0"){
			$foresightBag = "-There is a bag at ".$bag['posx'].", ".$bag['posy']."<br />";
		}
		$findOre = db_query("SELECT * FROM ore WHERE (`xpos` BETWEEN ? AND ?) AND (`ypos` BETWEEN ? AND ?)", [$xbottom, $xtop, $ybottom, $ytop]);
		$there = db_num_rows($findOre);
		if($there > "0"){
			$ore = db_fetch_assoc($findOre);
			$foresightOre = "-An Ore was spotted at ".$ore['xpos'].",".$ore['ypos']."<br />";
		}
		print("fillDiv('foresightDiv','".$foresightBag."".$foresightOre."');");

?>