<?php
session_name("fallenimmortals");
session_start();
include('db.php');
$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);

if($_POST['bagid'] != NULL){
	$findbagid = db_query("SELECT * FROM bagdrop WHERE id=?", [$_POST['bagid']]);
	$bag = db_fetch_assoc($findbagid);
	
	if(db_num_rows($findbagid) < 1){
		die();
	}
	
	$bagRel = explode(', ', $bag['relativeLoc']);
	$charRel = explode(', ', $char['relativeLoc']);
	$bagXtop = $bagRel[0]+32;
	$bagXbottom = $bagRel[0]-32;
	$bagYtop = $bagRel[1]+32;
	$bagYbottom = $bagRel[1]-32;
	
	if(($bagXtop >= $charRel[0] && $bagXbottom <= $charRel[0]) && ($bagYtop >= $charRel[1] && $bagYbottom <= $charRel[1])){
		
	}else{
		die("alert('You must move closer to the bag.');");
	}
	
	if($bag['name'] == NULL){
		print("alert('You didn\'t catch the bag.');");
	}elseif($bag['posx'] != $char['posx'] || $bag['posy'] != $char['posy']){
		print("alert('You are not in the right location to collect this bag.');");
	}else{
		$rand = rand(1,100);
		if($rand == "1"){
			print("alert('You open the bag for 1 cash!');");
			$give = db_query("UPDATE characters SET cash=cash+'1' WHERE id=?", [$char['id']]);
			$messagechat = "<strong><font color=\'#D2691E\'>".$char['username']." found 1 Cash from the bag at ".$bag['posx'].", ".$bag['posy']."!</font></strong><br />";
                $query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')", [$date, $char['username'], $messagechat]);
		}elseif($rand > "1" && $rand <= "33"){
			$goldrand = rand(1,100000);
			print("alert('You open the bag for ".number_format($goldrand)." gold!');");
			$give = db_query("UPDATE characters SET gold=gold+? WHERE id=?", [$goldrand, $char['id']]);
			$messagechat = "<strong><font color=\'#D2691E\'>".$char['username']." found ".number_format($goldrand)." gold from the bag at ".$bag['posx'].", ".$bag['posy']."!</font></strong><br />";
                $query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')", [$date, $char['username'], $messagechat]);
		}elseif($rand > "33" && $rand <= "66"){
			$statpointrand = rand(1,25);
			print("alert('You open the bag for ".$statpointrand." Statpoints!');");
			$give = db_query("UPDATE characters SET stats=stats+? WHERE id=?", [$statpointrand, $char['id']]);
			$messagechat = "<strong><font color=\'#D2691E\'>".$char['username']." found ".number_format($statpointrand)." Statpoints from the bag at ".$bag['posx'].", ".$bag['posy']."!</font></strong><br />";
                $query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')", [$date, $char['username'], $messagechat]);
		}elseif($rand > "66" && $rand <= "100"){
			$bloodrand = rand(1,100);
			print("alert('You open the bag for ".$bloodrand." blood!');");
			$give = db_query("UPDATE characters SET blood=blood+? WHERE id=?", [$bloodrand, $char['id']]);
			$messagechat = "<strong><font color=\'#D2691E\'>".$char['username']." found ".number_format($bloodrand)." blood from the bag at ".$bag['posx'].", ".$bag['posy']."!</font></strong><br />";
                $query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')", [$date, $char['username'], $messagechat]);
		}
		$deleteBag = db_query("DELETE FROM bagdrop WHERE id=?", [$_POST['bagid']]);
	}
}else{
	print("alert('Error.');");
}
		$findBagDrops = db_query("SELECT * FROM bagdrop WHERE posx=? and posy=?", [$char['posx'], $char['posy']]);
		$bagLoc = "";
		while($bag = db_fetch_assoc($findBagDrops)){
			$bagRel = explode(', ', $bag['relativeLoc']);
			$bagLoc .= "<div style=\'position:absolute;left:".$bagRel[0]."px;bottom:".$bagRel[1]."px;width:32px;height:32px;background-image:url(/images/map/locations/bag.png);\' onclick=\'grabBag(".$bag['id'].")\'></div>";
		}
		print("fillDiv('bagLocations','".$bagLoc."');");
include('updatestats.php');
?>