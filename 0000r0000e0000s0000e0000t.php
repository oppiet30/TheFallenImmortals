<?php

include('db-conn.php');

$date = time();



$gettemple = db_query("SELECT * FROM temple");

$temple = db_fetch_assoc($gettemple);

$update1 = db_query("UPDATE characters SET temple='0'");



$getUsers = db_query("SELECT * FROM characters");

while($userMana = db_fetch_array($getUsers)){

    $getchar = db_query("SELECT * FROM characters WHERE id=?", [$userMana['id']]);

    $char = db_fetch_assoc($getchar);

    $getequip = db_query("SELECT * FROM inventory WHERE username=? AND equipped='Yes'", [$char['username']]);

    $eqintbon = 0;

    if(db_num_rows($getequip) > "0")

    {

        while($equip = db_fetch_array($getequip))

        {

            $eqintbon += $equip['intelligence'];

        }

    }



    $charint = $char['intelligence'] + $eqintbon;

    

    $updateMana = db_query("UPDATE characters SET mana=? WHERE username=?", [$charint, $char['username']]);

}



$addAttempt = db_query("UPDATE characters SET vodooattempt='0'");

$addlogin = db_query("UPDATE characters SET dailylogin='0'");





$updatedBlessings1 = "None, Buy, Buy, Buy, Buy, Buy, Buy, Buy, Buy"; 

$runblessingReset1 = db_query("UPDATE characters SET blessing=? WHERE affinitys='1'", [$updatedBlessings1]);

$updatedBlessings2 = "None, None, Buy, Buy, Buy, Buy, Buy, Buy, Buy"; 

$runblessingReset2 = db_query("UPDATE characters SET blessing=? WHERE affinitys='2'", [$updatedBlessings2]);

$updatedBlessings3 = "None, None, None, Buy, Buy, Buy, Buy, Buy, Buy"; 

$runblessingReset3 = db_query("UPDATE characters SET blessing=? WHERE affinitys='3'", [$updatedBlessings3]);

$updatedBlessings4 = "None, None, None, None, Buy, Buy, Buy, Buy, Buy"; 

$runblessingReset4 = db_query("UPDATE characters SET blessing=? WHERE affinitys='4'", [$updatedBlessings4]);

$updatedBlessings5 = "None, None, None, None, None, Buy, Buy, Buy, Buy"; 

$runblessingReset5 = db_query("UPDATE characters SET blessing=? WHERE affinitys='5'", [$updatedBlessings5]);

$updatedBlessings6 = "None, None, None, None, None, None, Buy, Buy, Buy"; 

$runblessingReset6 = db_query("UPDATE characters SET blessing=? WHERE affinitys='6'", [$updatedBlessings6]);

$updatedBlessings7 = "None, None, None, None, None, None, None, Buy, Buy"; 

$runblessingReset7 = db_query("UPDATE characters SET blessing=? WHERE affinitys='7'", [$updatedBlessings7]);

$updatedBlessings8 = "None, None, None, None, None, None, None, None, Buy"; 

$runblessingReset8 = db_query("UPDATE characters SET blessing=? WHERE affinitys='8'", [$updatedBlessings8]);

$updatedBlessings9 = "None, None, None, None, None, None, None, None, None"; 

$runblessingReset9 = db_query("UPDATE characters SET blessing=? WHERE affinitys='9'", [$updatedBlessings9]);





$messagechat = "<strong><font color=\'#00FF00\'>(System): Daily reset occured. Blessings reset, Temple reset, Mana rejuvinated.</font></strong><br />";

$query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `message`, `to`) VALUES (?, '3', ?, 'Chatroom')", [$date, $messagechat]);

?>