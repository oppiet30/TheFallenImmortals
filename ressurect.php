<?php
session_name("icsession");
session_start();
include('db.php');

$getchar = mysqli_query($conn, "SELECT * FROM characters WHERE id='".$_SESSION['userid']."'") or die(mysqli_error($conn));
$char = mysqli_fetch_assoc($getchar);
$charend = $char['endurance'];
$date = time();

$getinv = mysqli_query($conn, "SELECT * FROM inventory WHERE username='".$charname."' AND endurance>'0' AND equipped='Yes'");
while($inv = mysqli_fetch_array($getinv))
{
    $charend += $inv['endurance'];
}
$blessingStats = explode(', ', $char['blessing']);
if (in_array('Constitution', $blessingStats)) 
{ 
    $result = mysqli_query($conn, "SELECT level FROM affinity WHERE name='Constitution'"); 
    $level = mysqli_fetch_assoc($result); 
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
    $result = mysqli_query($conn, "SELECT level FROM affinity WHERE name='Constitution II'"); 
    $level = mysqli_fetch_assoc($result); 
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
    $result = mysqli_query($conn, "SELECT level FROM affinity WHERE name='Constitution III'"); 
    $level = mysqli_fetch_assoc($result); 
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
    $result = mysqli_query($conn, "SELECT level FROM affinity WHERE name='Constitution IV'"); 
    $level = mysqli_fetch_assoc($result); 
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
    $result = mysqli_query($conn, "SELECT level FROM affinity WHERE name='Constitution V'"); 
    $level = mysqli_fetch_assoc($result); 
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
    $takeGold = mysqli_query($conn, "UPDATE characters SET gold=gold-'".$charend."' WHERE username='".$char['username']."'");
}else{
    $addOn = "";
}
$ressurect = mysqli_query($conn, "UPDATE characters SET life='".$charend."', lastactive='".$date."' WHERE id='".$_SESSION['userid']."'");

print("fillDiv('displayArea','<center>You have been ressurected".$addOn."!<br /><a href=\'javascript: runAway();\'>Fight More</a></center>');");

include('updatestats.php');

print("fillDiv('statsArea','".$data."');");
print("document.ajaxchat.messagechat.focus();");
?>