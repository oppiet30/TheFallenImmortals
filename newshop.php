<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_name("fallenimmortals");
	session_start();
}
include('db.php');
include('varset.php');
include('active.php');

	$findForgeItems = $conn->prepare("SELECT * FROM forge WHERE username=?");
	$findForgeItems->bind_param("s", $char['username']);
	$findForgeItems->execute();
	$findForgeItemsResult = $findForgeItems->get_result();
	$data = "<center><b>Forge</b><br />";
	if($findForgeItemsResult->num_rows == "4"){
		$data .= "<strong>Ore:</strong> <select id=\'ore\' onchange=\'getResults()\'>";
		$data .= "<option>Nothing</option>";
		$data .= "<option value=\'copper\'>Copper</option>";
		$data .= "<option value=\'iron\'>Iron</option>";
		$data .= "<option value=\'steel\'>Steel</option>";
		$data .= "</select>";
	}else{
		$data .= "<strong>Item:</strong> <select id=\'item\' onchange=\'openItem()\'>";
		$data .= "<option>Nothing</option>";
		if($findForgeItemsResult->num_rows > "0"){
			$forgeType = $findForgeItemsResult->fetch_assoc();
			$findItems = $conn->prepare("SELECT * FROM inventory WHERE username=? AND equipped='No' AND type=?");
			$findItems->bind_param("ss", $char['username'], $forgeType['type']);
			$findItems->execute();
			$findItemsResult = $findItems->get_result();
		}else{
			$findItems = $conn->prepare("SELECT * FROM inventory WHERE username=? AND equipped='No'");
			$findItems->bind_param("s", $char['username']);
			$findItems->execute();
			$findItemsResult = $findItems->get_result();
		}
		while($inventory = $findItemsResult->fetch_array()){
			$itempower = number_format($inventory['strength'] + $inventory['dexterity'] + $inventory['endurance'] + $inventory['concentration'] + $inventory['intelligence']);
			if(substr($inventory['itemname'], 0, 3) == "(F)"){
			}else{
				$data .= "<option value=\'".$inventory['id']."\'>".$inventory['itemname']." - POWER: ".$itempower."</option>";
			}
		}
		$data .= "</select>";
	}
	$data .= "<table border=\'1px\'>";
	$data .= "<tr>";
	$data .= "<td width=\'130px\' height=\'150px\'><div id=\'itemDescription\'></div></td>";
	$data .= "<td width=\'400px\'><div id=\'inForge\'>";
	$findForgeItems = $conn->prepare("SELECT * FROM forge WHERE username=?");
	$findForgeItems->bind_param("s", $char['username']);
	$findForgeItems->execute();
	$findForgeItemsResult = $findForgeItems->get_result();
	if($findForgeItemsResult->num_rows == "0"){
		$data .= "No items in the forge.";
	}else{
		$data .= "<table>";
		$data .= "<tr><td><u>Name</u></td><td><u>Str:</u></td><td><u>Dex:</u></td><td><u>End:</u></td><td><u>Con:</u></td><td><u>Int:</u></td><td>&nbsp;</td></tr>";
		while($inventory = $findForgeItemsResult->fetch_array()){
		$data .= "<tr><td>". $inventory['itemname'] ."</td><td>". $inventory['strength'] ."</td><td>". $inventory['dexterity'] ."</td><td>". $inventory['endurance'] ."</td><td>". $inventory['concentration'] ."</td><td>". $inventory['intelligence'] ."</td><td>(<a href=\'javascript: removeItem(\"".$inventory['id']."\");\' title=\'Remove from Forge\'>X</a>)</td></tr>";
		}
		$data .= "</table>";
		
	}
	$data .= "<div id=\'forgeResults\'></div>";
	$data .= "</div></td>";
	$data .= "</tr>";
	$data .= "</table>";

print("fillDiv('displayArea','".$data."');");
?>