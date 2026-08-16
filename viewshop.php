<?php
session_name("fallenimmortals");
session_start();
include('db.php');

$checkItems = mysqli_query($conn, "SELECT * FROM shop WHERE value>'10000'");
$updateShopItems = $conn->prepare("UPDATE shop SET levelreq=? WHERE id=?");
while($newLevelReq = mysqli_fetch_assoc($checkItems)){
	$newReq = floor(($newLevelReq['strength'] + $newLevelReq['dexterity'] + $newLevelReq['endurance'] + $newLevelReq['intelligence'] + $newLevelReq['concentration']) * 0.03);
	$updateShopItems->bind_param("ii", $newReq, $newLevelReq['id']);
	$updateShopItems->execute() or die("alert('Unable to update levelreqs!');");
}

$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute();
$char = $getchar->get_result()->fetch_assoc();
$charname = $char['username'];
$data = "";

if(isset($_POST['sellid']))   //selling
{
    $idinven = $_POST['sellid'];
    $check = $conn->prepare("SELECT * FROM inventory WHERE id=? AND username=?");
    $check->bind_param("is", $idinven, $charname);
    $check->execute() or die("Not an existing item in your inventory.");
    $selling = $check->get_result()->fetch_assoc();
    $sellid = $selling['id'];
    $findShopThingy = $conn->prepare("SELECT * FROM shop WHERE itemname=?");
    $findShopThingy->bind_param("s", $selling['itemname']);
    $findShopThingy->execute();
    $findShopThingyResult = $findShopThingy->get_result();
    if($findShopThingyResult->num_rows > 0){
    	$sellworth = floor($selling['value'] - ($selling['value'] * (($char['tradeskill'] - 900)/1000)));
    }else{
    	$sellworth = floor($selling['value'] * ($char['tradeskill'] / 1000));
    }
    $sellname = $selling['itemname'];
    $data .= "You sold ".$sellname." for ".number_format($sellworth)." gold, to the shop!<br /><br />";
    $delete = $conn->prepare("DELETE FROM inventory WHERE id=?");
    $delete->bind_param("i", $sellid);
    $delete->execute();
    $gold = $char['gold'] + $sellworth;
    $addgold = $conn->prepare("UPDATE characters SET gold=? WHERE username=?");
    $addgold->bind_param("is", $gold, $charname);
    $addgold->execute();
}
elseif(isset($_POST['itemid']))   //Buying
{
    $idshop = $_POST['itemid'];
    $check = $conn->prepare("SELECT * FROM shop WHERE id=?");
    $check->bind_param("i", $idshop);
    $check->execute() or die("Failed to buy item.");
    $buying = $check->get_result()->fetch_assoc();
    if($buying['value'] > $char['gold']){
        $data .= "You cannot buy an item that you cannot afford.<br /><br />";
    }else{
        $buyworth = floor($buying['value'] - ($buying['value'] * (($char['tradeskill'] - 900)/1000)));
        $buyname = $buying['itemname'];
        $data .= "You bought ".$buyname." for ".number_format($buyworth)." gold, from the shop!<br /><br />";
        $makeitem = $conn->prepare("INSERT INTO inventory (`username`, `itemname`, `levelreq`, `power`, `type`, `strength`, `dexterity`, `endurance`, `intelligence`, `concentration`, `value`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $makeitem->bind_param("ssiisiiiiii", $char['username'], $buying['itemname'], $buying['levelreq'], $buying['power'], $buying['type'], $buying['strength'], $buying['dexterity'], $buying['endurance'], $buying['intelligence'], $buying['concentration'], $buying['value']);
        $makeitem->execute();
        $gold = $char['gold'] - $buyworth;
        $addgold = $conn->prepare("UPDATE characters SET gold=? WHERE username=?");
        $addgold->bind_param("is", $gold, $charname);
        $addgold->execute();
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
	$querty = $conn->prepare("SELECT * FROM inventory WHERE username=? AND equipped='No' ORDER BY value");
	$querty->bind_param("s", $charname);
	$querty->execute();
	$quertyResult = $querty->get_result();
	$data .= "<option>Nothing</option>";
    while($inventory = $quertyResult->fetch_array()){
        $data .= "<option value=\'".$inventory['id']."\'>".$inventory['itemname']."</option>";
    }
    $data .= "</select><div id=\'sellLink\'></div><br />";
	$data .= "<div id=\'sellDesc\'></div></center>";
    


print("fillDiv('displayArea','".$data."');");
include('updatestats.php');
?>