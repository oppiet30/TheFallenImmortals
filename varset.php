<?php
error_reporting(E_ALL ^ E_NOTICE);
$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute() or die($conn->error);
$char = $getchar->get_result()->fetch_assoc();

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
$charstrmod = 0;
$chardexmod = 0;
$charendmod = 0;
$charintmod = 0;
$charconmod = 0;
$getinv = $conn->prepare("SELECT * FROM inventory WHERE username=? AND equipped='Yes'");
$getinv->bind_param("s", $charname);
$getinv->execute();
$getinvResult = $getinv->get_result();
while($inv = $getinvResult->fetch_array())
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
	$getguild = $conn->prepare("SELECT * FROM guilds WHERE name=?");
	$getguild->bind_param("s", $charguild);
	$getguild->execute();
	$guild = $getguild->get_result()->fetch_assoc();
}
?>