<?php
session_name("icsession");
session_start();
include('db.php');

$itemid = $_POST['itemid'];
$getitem = mysqli_query($conn, "SELECT * FROM inventory WHERE id='".$itemid."'");
if(mysqli_num_rows($getitem) == "1")    //Item exists
{
    $item = mysql_fetch_assoc($getitem);
    if($charname == $item['username'])    //Item belongs to manipulating player
    {
            $itemName = explode(',', $char['equipped']);
            if($item['type'] == "Weapon")      //For Accommodations display.
            {
                $itemName[0] = "None";
            }
            elseif($item['type'] == "Armour")      //For Accommodations display.
            {
                $itemName[1] = "None";
            }
            elseif($item['type'] == "Gloves")      //For Accommodations display.
            {
                $itemName[2] = "None";
            }
            elseif($item['type'] == "Leggings")      //For Accommodations display.
            {
                $itemName[3] = "None";
            }
            elseif($item['type'] == "Boots")      //For Accommodations display.
            {
                $itemName[4] = "None";
            }
            $accommodations = "".$itemName[0].",".$itemName[1].",".$itemName[2].",".$itemName[3].",".$itemName[4].",";
            $accommodationUpdate = mysqli_query($conn, "UPDATE characters SET equipped='".$accommodations."' WHERE id='".$_SESSION['userid']."'") or die(mysql_error("Error updating User accommodation. Please tell the admin."));

        $unequip = mysqli_query($conn, "UPDATE inventory SET equipped='No' WHERE id='".$itemid."'");
        print("viewInventory();");
        include('updatestats.php');
    }
}
?>