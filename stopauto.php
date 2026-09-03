<?php
session_name("icsession");
session_start();
include('db.php');
include('varset.php');
$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);

$stopAuto = db_query("UPDATE characters SET auto='0' WHERE id=?", [$char['id']]);
$data = "<center>Auto stopped! <a href=\'Javascript: runAway();\'>Back to Fight</a>!</center>";
print("fillDiv('displayArea','".$data."');");
?>