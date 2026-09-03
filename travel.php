<?php
session_name("icsession");
session_start();
include('db.php');


$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);
$charrel = explode(", ", $char['relativeLoc']);

$findMap = db_query("SELECT * FROM map WHERE xpos=? and ypos=?", [$char['posx'], $char['posy']]);
$map = db_fetch_assoc($findMap);	
		//Show the teleporter if purchased
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
$data = "<div id=\"MainCanvas\" oncontextmenu=\"rightClickList(event);return false;\" style=\"position:relative; top:110px; width:1050px; height:550px; background-color:#000000; background-image:url(".$map['background'].");\">";
	$data .= "<div id=\'mineLocations\'>";
	$findOre = db_query("SELECT * FROM ore WHERE xpos=? and ypos=?", [$char['posx'], $char['posy']]);
	while($ore = db_fetch_assoc($findOre)){
		$oreRel = explode(', ', $ore['relativeLoc']);
		$data .= "<div alt=\"Mining Ore\" style=\'position:absolute;left:".$oreRel[0]."px;top:".$oreRel[1]."px;width:33px;height:62px;z-index:1;background-image:url(images/map/locations/mining.png);\' onclick=\'mineOre(".$ore['id'].")\'></div>";
	}
	$data .= "</div>";

	$data .= "<div id=\'bagLocations\'>";
	$findBagDrops = db_query("SELECT * FROM bagdrop WHERE posx=? and posy=?", [$char['posx'], $char['posy']]);
	while($bag = db_fetch_assoc($findBagDrops)){
		$bagRel = explode(', ', $bag['relativeLoc']);
		$data .= "<div alt=\"Bag Drop\" style=\'position:absolute;left:".$bagRel[0]."px;top:".$bagRel[1]."px;width:32px;height:32px;background-image:url(images/map/locations/bag.png);\' onclick=\'grabBag(".$bag['id'].")\'></div>";
	}
	$data .= "</div>";

	$data .= "<div id=\'demonLocations\'>";
	$findDemons = db_query("SELECT * FROM demons WHERE xpos=? and ypos=? and health>'0'", [$char['posx'], $char['posy']]);
	while($demon = db_fetch_assoc($findDemons)){
		$demonRel = explode(', ', $demon['relativeLoc']);
		$data .= "<div alt=\"Demon Spawn\" style=\'position:absolute;left:".$demonRel[0]."px;top:".$demonRel[1]."px;width:45px;height:45px;z-index:1;background-image:url(".$demon['image'].");\' onclick=\'fightDemon(".$demon['id'].")\'></div>";
	}
	$data .= "</div>";

	$data .= "<div id=\'otherPlayers\'>";
	$time = time() - "600";
	$findPlayers = db_query("SELECT * FROM characters WHERE posx=? and posy=? and username<>? and lastactive>?", [$char['posx'], $char['posy'], $char['username'], $time]);
	while($player = db_fetch_assoc($findPlayers)){
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