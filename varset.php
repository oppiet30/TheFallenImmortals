<?php
error_reporting(E_ALL ^ E_NOTICE);
$getchar = mysqli_query($conn, "SELECT * FROM characters WHERE id='".$_SESSION['userid']."'") or die(mysql_error());
$char = mysqli_fetch_assoc($getchar);

//Character & Player classifications
$charname = $char['username'];
$charulvl = $char['userlevel'];
$charclass = $char['class'];
$charguild = $char['guild'];
$charstatus = $char['status'];
$charip = $char['ip'];

//Level and experience
$charlvl = $char['level'];
$charexp = $char['expacq'];
$chartnl = $char['expreq'];

//Character stats
$charlevel = $char['level'];
$charstats = $char['stats'];
$charstr = $char['strength'];
$chardex = $char['dexterity'];
$charend = $char['endurance'];
$charint = $char['intelligence'];
$charcon = $char['concentration'];
$charlife = $char['life'];
$charauto = $char['auto'];

//Cash modified fields
$charcash = $char['cash'];
$charstatmulti = $char['statmult'] / 100;

//Modified stats from item bonuses (For display purposes only)
$getinv = mysqli_query($conn, "SELECT * FROM inventory WHERE username='".$charname."' AND equipped='Yes'");
while($inv = mysqli_fetch_array($getinv))
{
	$charstrmod += $inv['strength'];
	$chardexmod += $inv['dexterity'];
	$charendmod += $inv['endurance'];
	$charintmod += $inv['intelligence'];
	$charconmod += $inv['concentration'];
}
$charstrmod += $charstr;
$chardexmod += $chardex;
$charendmod += $charend;
$charintmod += $charint;
$charconmod += $charcon;

//Location
$charloc = $char['location'];
$charx = $char['posx'];
$chary = $char['posy'];

//Anything that doesn't fall under the other categories
$chargold = $char['gold'];
$charbank = $char['bank'];

//Characters Guild
if($charguild != "None")
{
	$getguild = mysqli_query($conn, "SELECT * FROM guilds WHERE name='".$charguild."'");
	$guild = mysqli_fetch_assoc($getguild);
}
?>