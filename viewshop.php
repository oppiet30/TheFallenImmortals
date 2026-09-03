<?php
session_name("icsession");
session_start();
include('db.php');

$checkItems = db_query("SELECT * FROM shop WHERE value>'10000'");
while($newLevelReq = db_fetch_assoc($checkItems)){
	$newReq = floor(($newLevelReq['strength'] + $newLevelReq['dexterity'] + $newLevelReq['endurance'] + $newLevelReq['intelligence'] + $newLevelReq['concentration']) * 0.03);
	$updateShopItems = db_query("UPDATE shop SET levelreq=? WHERE id=?", [$newReq, $newLevelReq['id']]);
}

$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);
$charname = $char['username'];
$data = "";

if(isset($_POST['sellid']))   //selling
{
    $idinven = $_POST['sellid'];
    $check = db_query("SELECT * FROM inventory WHERE id=? AND username=?", [$idinven, $charname]);
    $selling = db_fetch_assoc($check);
    $sellid = $selling['id'];
    $findShopThingy = db_query("SELECT * FROM shop WHERE itemname=?", [$selling['itemname']]);
    if(db_num_rows($findShopThingy) > 0){
    	$sellworth = floor($selling['value'] - ($selling['value'] * (($char['tradeskill'] - 900)/1000)));
    }else{
    	$sellworth = floor($selling['value'] * ($char['tradeskill'] / 1000));
    }
    $sellname = $selling['itemname'];
    $data .= "You sold ".$sellname." for ".number_format($sellworth)." gold, to the shop!<br /><br />";
    $delete = db_query("DELETE FROM inventory WHERE id=?", [$sellid]);
    $gold = $char['gold'] + $sellworth;
    $addgold = db_query("UPDATE characters SET gold=? WHERE username=?", [$gold, $charname]);
}
elseif(isset($_POST['itemid']))   //Buying
{
    $idshop = $_POST['itemid'];
    $check = db_query("SELECT * FROM shop WHERE id=?", [$idshop]);
    $buying = db_fetch_assoc($check);
    if($buying['value'] > $char['gold']){
        $data .= "You cannot buy an item that you cannot afford.<br /><br />";
    }else{
        $buyworth = floor($buying['value'] - ($buying['value'] * (($char['tradeskill'] - 900)/1000)));
        $buyname = $buying['itemname'];
        $data .= "You bought ".$buyname." for ".number_format($buyworth)." gold, from the shop!<br /><br />";
        $makeitem = db_query("INSERT INTO inventory (`username`, `itemname`, `levelreq`, `power`, `type`, `strength`, `dexterity`, `endurance`, `intelligence`, `concentration`, `value`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [$char['username'], $buying['itemname'], $buying['levelreq'], $buying['power'], $buying['type'], $buying['strength'], $buying['dexterity'], $buying['endurance'], $buying['intelligence'], $buying['concentration'], $buying['value']]);
        $gold = $char['gold'] - $buyworth;
        $addgold = db_query("UPDATE characters SET gold=? WHERE username=?", [$gold, $charname]);
    }
}

    $data .= "<center>Buy:<br /> <table>";
	$data .= "<tr><td>Combat Style:<select id=\'style\' onchange=\'buyStyle()\'>";
	$data .= "<option>Nothing</option>";
	$data .= "<option>Fighter</option>";
	$data .= "<option>Mage</option>";
    $data .= "</select></td><td><div id=\'itemType\'></div></td>";
	$data .= "</tr></table>";
	$data .="<div id=\'item\'></div><br />";
	$data .= "<div id=\'buyDesc\'></div></center>";
    //Shop menu
    $data .= "<center>Sell: <select id=\'sellid\' onchange=\'sellDesc()\'>";
	$querty = db_query("SELECT * FROM inventory WHERE username=? AND equipped='No' ORDER BY value", [$charname]);
	$data .= "<option>Nothing</option>";
    while($inventory = db_fetch_array($querty)){
        $data .= "<option value=\'".$inventory['id']."\'>".$inventory['itemname']."</option>";
    }
    $data .= "</select><div id=\'sellLink\'></div><br />";
	$data .= "<div id=\'sellDesc\'></div></center>";
    


print("fillDiv('displayArea','".$data."');");
include('updatestats.php');
?>