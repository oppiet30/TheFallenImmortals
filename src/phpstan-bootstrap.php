<?php

/**
 * PHPStan bootstrap: declares the global variables that the legacy procedural
 * game files establish via include('db.php') / include('varset.php') etc.
 *
 * Because the game script files run in the global scope and rely on
 * include() to populate shared variables, PHPStan otherwise flags them as
 * undefined in each file. This file uses `global` so PHPStan treats these
 * as possibly-set globals across the codebase.
 */

global $char, $guild, $muted, $bonus, $date, $time;
global $charname, $charulvl, $charclass, $charguild, $charstatus, $charip;
global $charlvl, $charexp, $chartnl, $charlevel, $charstats;
global $charstr, $chardex, $charend, $charint, $charcon, $charlife, $charauto;
global $charcash, $charstatmulti, $charloc, $charx, $chary, $chargold, $charbank;
global $oponent, $oponentname, $oponentstr, $oponentdex, $oponentend, $oponentint, $oponentcon;
global $data, $newlvl, $eqstrbon, $eqdexbon, $eqendbon, $eqintbon, $eqconbon;
global $totalstr, $totaldex, $totalend, $totalint, $totalcon;
global $getchar, $getguild, $query, $setactive, $update, $rewards;

/** @var array<string,string>|null $char */
$char = $char ?? null;
/** @var array<string,string>|false|null $guild */
$guild = $guild ?? null;
/** @var int|string $date */
$date = $date ?? time();
