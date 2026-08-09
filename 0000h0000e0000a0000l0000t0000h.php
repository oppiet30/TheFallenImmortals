<?php
include('db-conn.php');

$getUsers = mysqli_query($conn, "SELECT * FROM characters");
while($char = mysqli_fetch_assoc($getUsers)){

	$charendmod = $char['endurance'];

	$getequip = mysqli_query($conn, "SELECT * FROM inventory WHERE username='".$char['username']."' AND equipped='Yes'");
	while($equip = mysqli_fetch_array($getequip))
	{
		$charendmod += $equip['endurance'];
	}

	if($char['life'] > "0" && $char['life'] < $charendmod){
		$regenAmount = ceil($charendmod * 0.01);
		$newLife = $char['life'] + $regenAmount;
		if($newLife > $charendmod){
			$newLife = $charendmod;
		}
		mysqli_query($conn, "UPDATE characters SET life='".$newLife."' WHERE id='".$char['id']."'");
	}
}
?>
