<?php
session_name("fallenimmortals");
session_start();
include('db.php');

$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute() or die($conn->error);
$char = $getchar->get_result()->fetch_assoc();
$charend = $char['endurance'];
$date = time();

$getinv = $conn->prepare("SELECT * FROM inventory WHERE username=? AND endurance>'0' AND equipped='Yes'");
$getinv->bind_param("s", $charname);
$getinv->execute();
$getinvResult = $getinv->get_result();
while($inv = $getinvResult->fetch_array())
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
    $takeGold = $conn->prepare("UPDATE characters SET gold=gold-? WHERE username=?");
    $takeGold->bind_param("is", $charend, $char['username']);
    $takeGold->execute();
}else{
    $addOn = "";
}
$ressurect = $conn->prepare("UPDATE characters SET life=?, lastactive=? WHERE id=?");
$ressurect->bind_param("iii", $charend, $date, $_SESSION['userid']);
$ressurect->execute();

print("fillDiv('displayArea','<center>You have been ressurected".$addOn."!<br /><a href=\'javascript: runAway();\'>Fight More</a></center>');");

include('updatestats.php');

print("fillDiv('statsArea','".$data."');");
print("document.ajaxchat.messagechat.focus();");
?>