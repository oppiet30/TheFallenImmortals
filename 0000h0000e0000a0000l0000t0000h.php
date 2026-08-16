<?php
include('db-conn.php');

$getUsers = mysqli_query($conn, "SELECT * FROM characters");
$getequip = $conn->prepare("SELECT * FROM inventory WHERE username=? AND equipped='Yes'");
$updateLife = $conn->prepare("UPDATE characters SET life=? WHERE id=?");
while($char = mysqli_fetch_assoc($getUsers)){

	$charendmod = $char['endurance'];

	$getequip->bind_param("s", $char['username']);
	$getequip->execute();
	$getequipResult = $getequip->get_result();
	while($equip = $getequipResult->fetch_array())
	{
		$charendmod += $equip['endurance'];
	}

	if($char['life'] > "0" && $char['life'] < $charendmod){
		$regenAmount = ceil($charendmod * 0.01);
		$newLife = $char['life'] + $regenAmount;
		if($newLife > $charendmod){
			$newLife = $charendmod;
		}
		$updateLife->bind_param("ii", $newLife, $char['id']);
		$updateLife->execute();
	}
}
?>
