<?php 
session_name("fallenimmortals");
session_start();
include('db.php');

if($_POST['buyid'] != "Nothing"){
	$data = "";
	$querty = db_query("SELECT * FROM shop WHERE id=?", [$_POST['buyid']]);
	$inventory = db_fetch_assoc($querty);
	$current = db_query("SELECT * FROM inventory WHERE type=? AND equipped='Yes' AND username=?", [$inventory['type'], $char['username']]);
	$equipped = db_fetch_assoc($current);
	$information = "Name: ".$equipped['itemname']." &#013; |Type: ".$equipped['type']." &#013; |Lvl Req: ".$equipped['levelreq']." &#013; |Str: ".$equipped['strength']." &#013; |Dex: ".$equipped['dexterity']." &#013; |End: ".$equipped['endurance']." &#013; |Int: ".$equipped['intelligence']." &#013; |Con: ".$equipped['concentration']." &#013; |Price: ".$equipped['value']."";
	
	
	$data .= "<center>[<a title=\'".$information."\'>?</a>] <a href=\'javascript: buyItem(\"".$inventory['id']."\");\'>Buy</a><table>";
    $data .= "<tr><td>Type:</td><td>".$inventory['type']."</td></tr>";
	$data .= "<tr><td>Level Req:</td><td>".$inventory['levelreq']."</td></tr>";
	$data .= "<tr><td>Str:</td><td>".$inventory['strength']."</td></tr>";
	$data .= "<tr><td>Dex:</td><td>".$inventory['dexterity']."</td></tr>";
	$data .= "<tr><td>End:</td><td>".$inventory['endurance']."</td></tr>";
	$data .= "<tr><td>Int:</td><td>".$inventory['intelligence']."</td></tr>";
	$data .= "<tr><td>Con:</td><td>".$inventory['concentration']."</td></tr>";
	$data .= "<tr><td>Price:</td><td>".$inventory['value']."</td></tr>";
	$data .= "</table></center>";

}else{
	$data = "Nothing!";
}

print("fillDiv('buyDesc','".$data."');");
include('updatestats.php');
?>