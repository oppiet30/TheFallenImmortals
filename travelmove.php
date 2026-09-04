<?php
session_name("fallenimmortals");
session_start();
include('db.php');
$time = time();
$setactive = db_query("UPDATE characters SET lastactive=? WHERE id=?", [$time, $_SESSION['userid']]);
$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);
$relLoc = explode(", ", $char['relativeLoc']);
$updateMap = "False";
$findMap = db_query("SELECT * FROM map WHERE xpos=? and ypos=?", [$char['posx'], $char['posy']]);
$map = db_fetch_assoc($findMap);
$findOre = db_query("SELECT * FROM ore WHERE xpos=? and ypos=?", [$char['posx'], $char['posy']]);
$ore = db_fetch_assoc($findOre);
$oreRel = explode(', ', $ore['relativeLoc']);
$oreXtop = $oreRel[0]+16;
$oreXbottom = $oreRel[0]-16;
$oreYtop = $oreRel[1]+16;
$oreYbottom = $oreRel[1]-16;
$findDemons = db_query("SELECT * FROM demons WHERE xpos=? and ypos=? and health>'0'", [$char['posx'], $char['posy']]);
$demon = db_fetch_assoc($findDemons);
$demonRel = explode(', ', $demon['relativeLoc']);
$demonXtop = $demonRel[0]+16;
$demonXbottom = $demonRel[0]-16;
$demonYtop = $demonRel[1]+16;
$demonYbottom = $demonRel[1]-16;



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
		if(db_num_rows($findOre) >= "1" && ($oreXtop >= $relLoc[0] && $oreXbottom <= $relLoc[0]) && ($oreYtop >= $relLoc[1] && $oreYbottom <= $relLoc[1])){
			die();
		}
		//Finding Demon collision
		if(db_num_rows($findDemons) >= "1" && ($demonXtop >= $relLoc[0] && $demonXbottom <= $relLoc[0]) && ($demonYtop >= $relLoc[1] && $demonYbottom <= $relLoc[1])){
			die();
		}
		if($relLoc[1]<0){
			//550 pixels from the top subtracting character height of 48 pixels
			$relLoc[1] = 502;
			db_query("UPDATE characters SET posy=posy+'1' WHERE username=?", [$char['username']]);
			$updateMap = "True";
		}
		db_query("UPDATE characters SET relativeLoc=?, ?, animationSequence=? WHERE username=?", [$relLoc[0], $relLoc[1], $animationSequence, $char['username']]);
		
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
		
		if(db_num_rows($findOre) >= "1" && ($oreXtop >= $relLoc[0] && $oreXbottom <= $relLoc[0]) && ($oreYtop >= $relLoc[1] && $oreYbottom <= $relLoc[1])){
			die();
		}
		//Finding Demon collision
		if(db_num_rows($findDemons) >= "1" && ($demonXtop >= $relLoc[0] && $demonXbottom <= $relLoc[0]) && ($demonYtop >= $relLoc[1] && $demonYbottom <= $relLoc[1])){
			die();
		}
		if($relLoc[0]<0){
			$relLoc[0] = 1018;
			db_query("UPDATE characters SET posx=posx-'1' WHERE username=?", [$char['username']]);
			$updateMap = "True";
		}
		db_query("UPDATE characters SET relativeLoc=?, ?, animationSequence=? WHERE username=?", [$relLoc[0], $relLoc[1], $animationSequence, $char['username']]);
		
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
		
		if(db_num_rows($findOre) >= "1" && ($oreXtop >= $relLoc[0] && $oreXbottom <= $relLoc[0]) && ($oreYtop >= $relLoc[1] && $oreYbottom <= $relLoc[1])){
			die();
		}
		//Finding Demon collision
		if(db_num_rows($findDemons) >= "1" && ($demonXtop >= $relLoc[0] && $demonXbottom <= $relLoc[0]) && ($demonYtop >= $relLoc[1] && $demonYbottom <= $relLoc[1])){
			die();
		}
		if($relLoc[0]>1018){
			$relLoc[0] = 0;
			db_query("UPDATE characters SET posx=posx+'1' WHERE username=?", [$char['username']]);
			$updateMap = "True";
		}
		db_query("UPDATE characters SET relativeLoc=?, ?, animationSequence=? WHERE username=?", [$relLoc[0], $relLoc[1], $animationSequence, $char['username']]);
		
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
		
		if(db_num_rows($findOre) >= "1" && ($oreXtop >= $relLoc[0] && $oreXbottom <= $relLoc[0]) && ($oreYtop >= $relLoc[1] && $oreYbottom <= $relLoc[1])){
			die();
		}
		//Finding Demon collision
		if(db_num_rows($findDemons) >= "1" && ($demonXtop >= $relLoc[0] && $demonXbottom <= $relLoc[0]) && ($demonYtop >= $relLoc[1] && $demonYbottom <= $relLoc[1])){
			die();
		}
		if($relLoc[1] > 502){
			$relLoc[1] = 0;
			db_query("UPDATE characters SET posy=posy-'1' WHERE username=?", [$char['username']]);
			$updateMap = "True";
		}
		db_query("UPDATE characters SET relativeLoc=?, ?, animationSequence=? WHERE username=?", [$relLoc[0], $relLoc[1], $animationSequence, $char['username']]);
		
	}else{
		die('alert("Invalid movement.");');
	}
	if($updateMap == "True"){
		$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
		$char = db_fetch_assoc($getchar);
		$findMap = db_query("SELECT * FROM map WHERE xpos=? and ypos=?", [$char['posx'], $char['posy']]);
		$map = db_fetch_assoc($findMap);
		print("
			var MainCanvas = document.getElementById('MainCanvas');
			MainCanvas.style.cssText = 'position:relative; top:110px; width:1050px; height:550px; background-color:#000000; background-image:url(".$map['background'].");';
		");
		
		
		print("fillDiv('dispLocation','Location: (".$char['posx'].", ".$char['posy'].")');");
		
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
			$oreLoc .= "<div alt=\"Mining Ore\" style=\'position:absolute;left:".$oreRel[0]."px;top:".$oreRel[1]."px;width:33px;height:62px;z-index:1;background-image:url(images/map/locations/mining.png);\' onclick=\'mineOre(".$ore['id'].")\'></div>";
		}
		print("fillDiv('mineLocations','".$oreLoc."');");
	}
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