<?php

include('db-conn.php');
$date = time();



$gettemple = mysqli_query($conn, "SELECT * FROM temple");

$temple = mysqli_fetch_assoc($gettemple);

$update1 = mysqli_query($conn, "UPDATE characters SET temple='0'");



$getUsers = mysqli_query($conn, "SELECT * FROM characters");

$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");

$getequip = $conn->prepare("SELECT * FROM inventory WHERE username=? AND equipped='Yes'");

$updateMana = $conn->prepare("UPDATE characters SET mana=? WHERE username=?");

while($userMana = mysqli_fetch_array($getUsers)){

    $getchar->bind_param("i", $userMana['id']);

    $getchar->execute();

    $char = $getchar->get_result()->fetch_assoc();

    $getequip->bind_param("s", $char['username']);

    $getequip->execute();

    $getequipResult = $getequip->get_result();

    $eqintbon = 0;

    if($getequipResult->num_rows > "0")

    {

        while($equip = $getequipResult->fetch_array())

        {

            $eqintbon += $equip['intelligence'];

        }

    }



    $charint = $char['intelligence'] + $eqintbon;



    $updateMana->bind_param("is", $charint, $char['username']);

    $updateMana->execute();

}



$addAttempt = mysqli_query($conn, "UPDATE characters SET vodooattempt='0'");

$addlogin = mysqli_query($conn, "UPDATE characters SET dailylogin='0'");





$updatedBlessings1 = "None, Buy, Buy, Buy, Buy, Buy, Buy, Buy, Buy"; 

$runblessingReset1 = $conn->prepare("UPDATE characters SET blessing=? WHERE affinitys='1'");

$runblessingReset1->bind_param("s", $updatedBlessings1);

$runblessingReset1->execute();

$updatedBlessings2 = "None, None, Buy, Buy, Buy, Buy, Buy, Buy, Buy"; 

$runblessingReset2 = $conn->prepare("UPDATE characters SET blessing=? WHERE affinitys='2'");

$runblessingReset2->bind_param("s", $updatedBlessings2);

$runblessingReset2->execute();

$updatedBlessings3 = "None, None, None, Buy, Buy, Buy, Buy, Buy, Buy"; 

$runblessingReset3 = $conn->prepare("UPDATE characters SET blessing=? WHERE affinitys='3'");

$runblessingReset3->bind_param("s", $updatedBlessings3);

$runblessingReset3->execute();

$updatedBlessings4 = "None, None, None, None, Buy, Buy, Buy, Buy, Buy"; 

$runblessingReset4 = $conn->prepare("UPDATE characters SET blessing=? WHERE affinitys='4'");

$runblessingReset4->bind_param("s", $updatedBlessings4);

$runblessingReset4->execute();

$updatedBlessings5 = "None, None, None, None, None, Buy, Buy, Buy, Buy"; 

$runblessingReset5 = $conn->prepare("UPDATE characters SET blessing=? WHERE affinitys='5'");

$runblessingReset5->bind_param("s", $updatedBlessings5);

$runblessingReset5->execute();

$updatedBlessings6 = "None, None, None, None, None, None, Buy, Buy, Buy"; 

$runblessingReset6 = $conn->prepare("UPDATE characters SET blessing=? WHERE affinitys='6'");

$runblessingReset6->bind_param("s", $updatedBlessings6);

$runblessingReset6->execute();

$updatedBlessings7 = "None, None, None, None, None, None, None, Buy, Buy"; 

$runblessingReset7 = $conn->prepare("UPDATE characters SET blessing=? WHERE affinitys='7'");

$runblessingReset7->bind_param("s", $updatedBlessings7);

$runblessingReset7->execute();

$updatedBlessings8 = "None, None, None, None, None, None, None, None, Buy"; 

$runblessingReset8 = $conn->prepare("UPDATE characters SET blessing=? WHERE affinitys='8'");

$runblessingReset8->bind_param("s", $updatedBlessings8);

$runblessingReset8->execute();

$updatedBlessings9 = "None, None, None, None, None, None, None, None, None"; 

$runblessingReset9 = $conn->prepare("UPDATE characters SET blessing=? WHERE affinitys='9'");

$runblessingReset9->bind_param("s", $updatedBlessings9);

$runblessingReset9->execute();





$messagechat = "<strong><font color=\'#00FF00\'>(System): Daily reset occurred. Blessings reset, Temple reset, Mana rejuvenated.</font></strong><br />";

$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `message`, `to`) VALUES (?, '3', ?, 'Chatroom')");

$query->bind_param("is", $date, $messagechat);

$query->execute();

?>