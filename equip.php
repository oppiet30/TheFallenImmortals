<?php
session_name("fallenimmortals");
session_start();
include('db.php');

$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);

$itemid = $_POST['itemid'];
if(!ctype_digit($itemid)){
	print("alert('Hack attempt has been logged.');");
	$toast = db_query("INSERT INTO hackreportlog (`information`) VALUES (? has attempted to alter equipment ID while equipping. The ID in inventory that was targeted was: ?)", [$char['username'], $itemid]);
	die();
}
$getitem = db_query("SELECT * FROM inventory WHERE id=? AND username=?", [$itemid, $char['username']]);
if(db_num_rows($getitem) == "1")    //Item exists
{
    $item = db_fetch_assoc($getitem);
    if($charname == $item['username'])    //Item belongs to manipulating player
    {
        if($charlevel >= $item['levelreq'])    //High enough level to equip
        {
            $itemName = explode(',', $char['equipped']);
            if($item['type'] == "Weapon")      //For Accommodations display.
            {
                $itemName[0] = $item['itemname'];
            }
            elseif($item['type'] == "Armour")      //For Accommodations display.
            {
                $itemName[1] = $item['itemname'];
            }
            elseif($item['type'] == "Gloves")      //For Accommodations display.
            {
                $itemName[2] = $item['itemname'];
            }
            elseif($item['type'] == "Leggings")      //For Accommodations display.
            {
                $itemName[3] = $item['itemname'];
            }
            elseif($item['type'] == "Boots")      //For Accommodations display.
            {
                $itemName[4] = $item['itemname'];
            }
            $accommodations = "".$itemName[0].",".$itemName[1].",".$itemName[2].",".$itemName[3].",".$itemName[4].",";
            $accommodationUpdate = db_query("UPDATE characters SET equipped=? WHERE id=?", [$accommodations, $_SESSION['userid']]);
            $unequip = db_query("UPDATE inventory SET equipped='No' WHERE type=? AND username=?", [$item['type'], $charname]);
            $equip = db_query("UPDATE inventory SET equipped='Yes' WHERE id=?", [$itemid]);
            print("viewInventory();");
            include('updatestats.php');
        }else{
        	print("alert('You are not a high enough level to equip this item!');");
        }
    }
}else{
	print("alert('Hack attempt has been logged.');");
	$toast = db_query("INSERT INTO hackreportlog (`information`) VALUES (? has attempted to alter equipment ID while equipping. The ID in inventory that was targeted was: ?)", [$char['username'], $itemid]);
	die();
}
?>