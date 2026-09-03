<?php
session_name("icsession");
session_start();
include('db.php');

$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);
$charend = $char['endurance'];
$date = time();

$getinv = db_query("SELECT * FROM inventory WHERE username=? AND endurance>'0' AND equipped='Yes'", [$charname]);
while($inv = db_fetch_array($getinv))
{
    $charend += $inv['endurance'];
}
$blessingStats = explode(', ', $char['blessing']);
if (in_array('Constitution', $blessingStats)) 
{ 
    $result = db_query("SELECT level FROM affinity WHERE name='Constitution'"); 
    $level = db_fetch_assoc($result); 
    $foo = 0; 
    for($i = 0, $count = count($blessingStats); $i < $count; $i++) 
    { 
        if($blessingStats[$i] == 'Constitution') 
        { 
            $foo += $level['level']; 
        } 
    } 
    $equation = $foo / 10; 
    $totalend += $charend * $equation; 
}
if (in_array('Constitution II', $blessingStats)) 
{ 
    $result = db_query("SELECT level FROM affinity WHERE name='Constitution II'"); 
    $level = db_fetch_assoc($result); 
    $foo = 0; 
    for($i = 0, $count = count($blessingStats); $i < $count; $i++) 
    { 
        if($blessingStats[$i] == 'Constitution II') 
        { 
            $foo += $level['level']; 
        } 
    } 
    $equation = $foo / 10; 
    $totalend += $charend * $equation; 
}
if (in_array('Constitution III', $blessingStats)) 
{ 
    $result = db_query("SELECT level FROM affinity WHERE name='Constitution III'"); 
    $level = db_fetch_assoc($result); 
    $foo = 0; 
    for($i = 0, $count = count($blessingStats); $i < $count; $i++) 
    { 
        if($blessingStats[$i] == 'Constitution III') 
        { 
            $foo += $level['level']; 
        } 
    } 
    $equation = $foo / 10; 
    $totalend += $charend * $equation;
}
if (in_array('Constitution IV', $blessingStats)) 
{ 
    $result = db_query("SELECT level FROM affinity WHERE name='Constitution IV'"); 
    $level = db_fetch_assoc($result); 
    $foo = 0; 
    for($i = 0, $count = count($blessingStats); $i < $count; $i++) 
    { 
        if($blessingStats[$i] == 'Constitution IV') 
        { 
            $foo += $level['level']; 
        } 
    } 
    $equation = $foo / 10; 
    $totalend += $charend * $equation;
}
if (in_array('Constitution V', $blessingStats)) 
{ 
    $result = db_query("SELECT level FROM affinity WHERE name='Constitution V'"); 
    $level = db_fetch_assoc($result); 
    $foo = 0; 
    for($i = 0, $count = count($blessingStats); $i < $count; $i++) 
    { 
        if($blessingStats[$i] == 'Constitution V') 
        { 
            $foo += $level['level']; 
        } 
    } 
    $equation = $foo / 10; 
    $totalend += $charend * $equation; 
}
$charend = floor($charend + $totalend);
if($char['level'] >= "50"){
    $addOn = " at the cost of ".number_format($charend)." gold";
    $takeGold = db_query("UPDATE characters SET gold=gold-? WHERE username=?", [$charend, $char['username']]);
}else{
    $addOn = "";
}
$ressurect = db_query("UPDATE characters SET life=?, lastactive=? WHERE id=?", [$charend, $date, $_SESSION['userid']]);

print("fillDiv('displayArea','<center>You have been ressurected".$addOn."!<br /><a href=\'javascript: runAway();\'>Fight More</a></center>');");

include('updatestats.php');

print("fillDiv('statsArea','".$data."');");
print("document.ajaxchat.messagechat.focus();");
?>