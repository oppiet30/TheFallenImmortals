<?php
session_name("icsession");
session_start();
include('db.php');
include('varset.php');
$getchar = mysqli_query($conn, "SELECT * FROM characters WHERE id='".$_SESSION['userid']."'") or die(mysqli_error());
$char = mysqli_fetch_assoc($getchar);

$stopAuto = mysqli_query($conn, "UPDATE characters SET auto='0' WHERE id='".$char['id']."'");
$data = "<center>Auto stopped! <a href=\'Javascript: runAway();\'>Back to Fight</a>!</center>";
print("fillDiv('displayArea','".$data."');");
?>