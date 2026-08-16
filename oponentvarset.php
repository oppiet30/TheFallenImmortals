<?php

//Character & Player classifications
$oponentname = $oponent['username'];
$oponentulvl = $oponent['userlevel'];
$oponentclass = $oponent['class'];
$oponentguild = $oponent['guild'];
$oponentstatus = $oponent['status'];
$oponentip = $oponent['ip'];

//Level and experience
$oponentlvl = $oponent['level'];
$oponentexp = $oponent['expacq'];
$oponenttnl = $oponent['expreq'];

//Character stats
$oponentlevel = $oponent['level'];
$oponentstats = $oponent['stats'];
$oponentstr = $oponent['strength'];
$oponentdex = $oponent['dexterity'];
$oponentend = $oponent['endurance'];
$oponentint = $oponent['intelligence'];
$oponentcon = $oponent['concentration'];
$oponentlife = $oponent['life'];
$oponentauto = $oponent['auto'];

//Cash modified fields
$oponentcash = $oponent['cash'];
$oponentstatmulti = $oponent['statmult'] / 100;

//Modified stats from item bonuses (For display purposes only)
$getinv = $conn->prepare("SELECT * FROM inventory WHERE username=? AND equipped='Yes'");
$getinv->bind_param("s", $oponentname);
$getinv->execute();
$getinvResult = $getinv->get_result();
while($inv = $getinvResult->fetch_array())
{
	$oponentstrmod += $inv['strength'];
	$oponentdexmod += $inv['dexterity'];
	$oponentendmod += $inv['endurance'];
	$oponentintmod += $inv['intelligence'];
	$oponentconmod += $inv['concentration'];
}
$oponentstrmod += $oponentstr;
$oponentdexmod += $oponentdex;
$oponentendmod += $oponentend;
$oponentintmod += $oponentint;
$oponentconmod += $oponentcon;
if($oponentlife > $oponentendmod)	$oponentlife = $oponentendmod;

//Location
$oponentloc = $oponent['location'];
$oponentx = $oponent['posx'];
$oponenty = $oponent['posy'];

//Anything that doesn't fall under the other categories
$oponentgold = $oponent['gold'];
$oponentbank = $oponent['bank'];

//Characters Guild
if($oponentguild != "None")
{
	$getguild = $conn->prepare("SELECT * FROM guilds WHERE name=?");
	$getguild->bind_param("s", $oponentguild);
	$getguild->execute();
	$oponentguild = $getguild->get_result()->fetch_assoc();
}
?>