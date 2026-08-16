<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include_once('functions.php');
$time = time();
$setactive = $conn->prepare("UPDATE characters SET lastactive=? WHERE id=?");
$setactive->bind_param("ii", $time, $_SESSION['userid']);
$setactive->execute();
$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute() or die($conn->error);
$char = $getchar->get_result()->fetch_assoc();
$relLoc = explode(", ", $char['relativeLoc']);
$updateMap = "False";
$findMap = $conn->prepare("SELECT * FROM map WHERE xpos=? and ypos=?");
$findMap->bind_param("ii", $char['posx'], $char['posy']);
$findMap->execute();
$map = $findMap->get_result()->fetch_assoc();
$findOre = $conn->prepare("SELECT * FROM ore WHERE xpos=? and ypos=?");
$findOre->bind_param("ii", $char['posx'], $char['posy']);
$findOre->execute();
$findOreResult = $findOre->get_result();
$oreBoxes = array();
while($ore = $findOreResult->fetch_assoc()){
	$oreRel = explode(', ', $ore['relativeLoc']);
	$oreBoxes[] = array($oreRel[0]-16, $oreRel[0]+16, $oreRel[1]-16, $oreRel[1]+16);
}
$findDemons = $conn->prepare("SELECT * FROM demons WHERE xpos=? and ypos=? and health>'0'");
$findDemons->bind_param("ii", $char['posx'], $char['posy']);
$findDemons->execute();
$findDemonsResult = $findDemons->get_result();
$demonBoxes = array();
while($demon = $findDemonsResult->fetch_assoc()){
	$demonRel = explode(', ', $demon['relativeLoc']);
	$demonBoxes[] = array($demonRel[0]-16, $demonRel[0]+16, $demonRel[1]-16, $demonRel[1]+16);
}

if(!isset($_POST['direction'])){
	die('alert("Invalid movement.");');
}else{
	if($_POST['direction'] == "up"){
		//for sprite up movement animation
		//top, right, bottom, left
		$up = array("top:-144px;left:0px;clip: rect(144px, 32px, 192px, 0px);","left:-32px;top:-144px;clip: rect(144px, 64px, 192px, 32px);","top:-144px;left:-64px;clip: rect(144px, 96px, 192px, 64px);","top:-144px;left:-96px;clip: rect(144px, 128px, 192px, 96px);");
		if($char['animationSequence'] < 3){
			$animationSequence = $char['animationSequence']+1;
		}else{
			$animationSequence = 0;
		}
		$animation = $up[$animationSequence];
		
	
		//Border detection
		if(($map['locationpadding'] == "-32px 0px 0px 0px" ||  $map['locationpadding'] == "-32px 0px 0px -32px" ||  $map['locationpadding'] == "-32px -32px 0px 0px") && $relLoc[1] <= "32"){
			die();
		}
		
		$relLoc[1] -= 16;
		
		//Finding Ore collision
		if(collidesWithBox($relLoc, $oreBoxes)){
			die();
		}
		//Finding Demon collision
		if(collidesWithBox($relLoc, $demonBoxes)){
			die();
		}
		if($relLoc[1]<0){
			//550 pixels from the top subtracting character height of 48 pixels
			$relLoc[1] = 502;
			$posUpdate = $conn->prepare("UPDATE characters SET posy=posy+'1' WHERE username=?");
			$posUpdate->bind_param("s", $char['username']);
			$posUpdate->execute();
			$updateMap = "True";
		}
		$relLocUpdate = $conn->prepare("UPDATE characters SET relativeLoc=?, animationSequence=? WHERE username=?");
		$relLocLiteral = $relLoc[0].", ".$relLoc[1];
		$relLocUpdate->bind_param("sis", $relLocLiteral, $animationSequence, $char['username']);
		$relLocUpdate->execute() or die($conn->error);
		
	}elseif($_POST['direction'] == "left"){
		/* top, right, bottom, left*/
		$left = array("top:-48px;left:0px;clip: rect(48px, 32px, 96px, 0px);","left:-32px;top:-48px;clip: rect(48px, 64px, 96px, 32px);","top:-48px;left:-64px;clip: rect(48px, 96px, 96px, 64px);","top:-48px;left:-96px;clip: rect(48px, 128px, 96px, 96px);");
		if($char['animationSequence'] < 3){
			$animationSequence = $char['animationSequence']+1;
		}else{
			$animationSequence = 0;
		}
		$animation = $left[$animationSequence];
	
		//Border detection
		if($relLoc[0] <= "32" && ($map['locationpadding'] == "0px 0px -32px -32px" || $map['locationpadding'] == "0px 0px 0px -32px" || $map['locationpadding'] == "-32px 0px 0px -32px")){
			die();
		}
		
		$relLoc[0] -= 16;
		
		if(collidesWithBox($relLoc, $oreBoxes)){
			die();
		}
		//Finding Demon collision
		if(collidesWithBox($relLoc, $demonBoxes)){
			die();
		}
		if($relLoc[0]<0){
			$relLoc[0] = 1018;
			$posUpdate = $conn->prepare("UPDATE characters SET posx=posx-'1' WHERE username=?");
			$posUpdate->bind_param("s", $char['username']);
			$posUpdate->execute();
			$updateMap = "True";
		}
		$relLocUpdate = $conn->prepare("UPDATE characters SET relativeLoc=?, animationSequence=? WHERE username=?");
		$relLocLiteral = $relLoc[0].", ".$relLoc[1];
		$relLocUpdate->bind_param("sis", $relLocLiteral, $animationSequence, $char['username']);
		$relLocUpdate->execute();
		
	}elseif($_POST['direction'] == "right"){
		/* top, right, bottom, left*/
		$right = array("top:-96px;left:0px;clip: rect(96px, 32px, 144px, 0px);","left:-32px;top:-96px;clip: rect(96px, 64px, 144px, 32px);","top:-96px;left:-64px;clip: rect(96px, 96px, 144px, 64px);","top:-96px;left:-96px;clip: rect(96px, 128px, 144px, 96px);");
		if($char['animationSequence'] < 3){
			$animationSequence = $char['animationSequence']+1;
		}else{
			$animationSequence = 0;
		}
		$animation = $right[$animationSequence];
	
		//Border detection
		if($relLoc[0] >= "986" && ($map['locationpadding'] == "0px -32px -32px 0px" || $map['locationpadding'] == "0px -32px 0px 0px" || $map['locationpadding'] == "-32px -32px 0px 0px")){
			die();
		}
		
		$relLoc[0] += 16;
		
		if(collidesWithBox($relLoc, $oreBoxes)){
			die();
		}
		//Finding Demon collision
		if(collidesWithBox($relLoc, $demonBoxes)){
			die();
		}
		if($relLoc[0]>1018){
			$relLoc[0] = 0;
			$posUpdate = $conn->prepare("UPDATE characters SET posx=posx+'1' WHERE username=?");
			$posUpdate->bind_param("s", $char['username']);
			$posUpdate->execute();
			$updateMap = "True";
		}
		$relLocUpdate = $conn->prepare("UPDATE characters SET relativeLoc=?, animationSequence=? WHERE username=?");
		$relLocLiteral = $relLoc[0].", ".$relLoc[1];
		$relLocUpdate->bind_param("sis", $relLocLiteral, $animationSequence, $char['username']);
		$relLocUpdate->execute();
		
	}elseif($_POST['direction'] == "down"){
	
		/* top, right, bottom, left*/
		$down = array("left:0px;clip: rect(0px, 32px, 48px, 0px);","left:-32px;clip: rect(0px, 64px, 48px, 32px);","left:-64px;clip: rect(0px, 96px, 48px, 64px);","left:-96px;clip: rect(0px, 128px, 48px, 96px);");
		if($char['animationSequence'] < 3){
			$animationSequence = $char['animationSequence']+1;
		}else{
			$animationSequence = 0;
		}
		$animation = $down[$animationSequence];
	
		//Border detection
		if($relLoc[1] >= "460" AND ($map['locationpadding'] == "0px 0px -32px 0px" || $map['locationpadding'] == "0px 0px -32px -32px" || $map['locationpadding'] == "0px -32px -32px 0px")){
			die();
		}
	
		$relLoc[1] += 16;
		
		if(collidesWithBox($relLoc, $oreBoxes)){
			die();
		}
		//Finding Demon collision
		if(collidesWithBox($relLoc, $demonBoxes)){
			die();
		}
		if($relLoc[1] > 502){
			$relLoc[1] = 0;
			$posUpdate = $conn->prepare("UPDATE characters SET posy=posy-'1' WHERE username=?");
			$posUpdate->bind_param("s", $char['username']);
			$posUpdate->execute();
			$updateMap = "True";
		}
		$relLocUpdate = $conn->prepare("UPDATE characters SET relativeLoc=?, animationSequence=? WHERE username=?");
		$relLocLiteral = $relLoc[0].", ".$relLoc[1];
		$relLocUpdate->bind_param("sis", $relLocLiteral, $animationSequence, $char['username']);
		$relLocUpdate->execute();
		
	}else{
		die('alert("Invalid movement.");');
	}
	if($updateMap == "True"){
		$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute() or die($conn->error);
		$char = $getchar->get_result()->fetch_assoc();
		$findMap = $conn->prepare("SELECT * FROM map WHERE xpos=? and ypos=?");
		$findMap->bind_param("ii", $char['posx'], $char['posy']);
		$findMap->execute();
		$map = $findMap->get_result()->fetch_assoc();
		print("
			var MainCanvas = document.getElementById('MainCanvas');
			MainCanvas.style.cssText = 'position:relative; top:110px; width:1050px; height:550px; background-color:#000000; background-image:url(".$map['background'].");';
		");
		
		
		print("fillDiv('dispLocation','Location: (".$char['posx'].", ".$char['posy'].")');");
		
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
			$oreLoc .= "<div alt=\"Mining Ore\" style=\'position:absolute;left:".$oreRel[0]."px;top:".$oreRel[1]."px;width:33px;height:62px;z-index:1;background-image:url(images/map/locations/mining.png);\' onclick=\'mineOre(".$ore['id'].")\'></div>";
		}
		print("fillDiv('mineLocations','".$oreLoc."');");
	}
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
	
	
	
	
	
	print("
	var charLocation = document.getElementById('charLocation');
	charLocation.style.cssText = 'position:absolute;left:".$relLoc[0]."px;top:".$relLoc[1]."px;width:32px;height:48px;transition: 0.21s;-webkit-transition: 0.21s;';
	
	var charDiv = document.getElementById('charDiv');
	charDiv.style.cssText = 'position:absolute;width:130px;height:194px;background-image:url(".$char['charimage'].");".$animation."';
	
	var charHair = document.getElementById('charHair');
	charHair.style.cssText = 'position:absolute;width:130px;height:194px;background-image:url(".$char['charhair'].");';
	
	var charLeggings = document.getElementById('charLeggings');
	charLeggings.style.cssText = 'position:absolute;width:130px;height:194px;background-image:url(".$char['charleggings'].");';
	");
}
?>