<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include('varset.php');
$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute() or die($conn->error);
$char = $getchar->get_result()->fetch_assoc();

$stopAuto = $conn->prepare("UPDATE characters SET auto='0' WHERE id=?");
$stopAuto->bind_param("i", $char['id']);
$stopAuto->execute();
$data = "<center>Auto stopped! <a href=\'Javascript: runAway();\'>Back to Fight</a>!</center>";
print("fillDiv('displayArea','".$data."');");
?>