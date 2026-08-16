<?php
$getAffinity = $conn->prepare("SELECT * FROM affinity WHERE name=?");
$getmods = $conn->prepare("SELECT * FROM inventory WHERE username=? AND equipped='Yes'");
$getmods->bind_param("s", $char['username']);
$getmods->execute();
$getmodsResult = $getmods->get_result();
while($mods = $getmodsResult->fetch_array())
{
	if($mods['strength'] > "0")
	{
		$charstr = floor($charstr + $mods['strength']);
	}
	if($mods['dexterity'] > "0")
	{
		$chardex = floor($chardex + $mods['dexterity']);
	}
	if($mods['endurance'] > "0")
	{
		$charend = floor($charend + $mods['endurance']);
	}
	if($mods['intelligence'] > "0")
	{
		$charint = floor($charint + $mods['intelligence']);
	}
	if($mods['concentration'] > "0")
	{
		$charcon = floor($charcon + $mods['concentration']);
	}
}

$blessing = explode(', ', $char['blessing']);
if($char['affinitys'] >= '1')
{
	if($blessing[0] == "Might"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[0] == "Speed"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[0] == "Constitution"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[0] == "Concentration"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[0] == "Intelligence"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[0] == "Might II"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[0] == "Speed II"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[0] == "Constitution II"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[0] == "Concentration II"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[0] == "Intelligence II"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[0] == "Might III"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[0] == "Speed III"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$equation = $level['level']/10;
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[0] == "Constitution III"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[0] == "Concentration III"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[0] == "Intelligence III"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[0] == "Might IV"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[0] == "Speed IV"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[0] == "Constitution IV"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[0] == "Concentration IV"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[0] == "Intelligence IV"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[0] == "Might V"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[0] == "Speed V"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[0] == "Constitution V"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[0] == "Concentration V"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[0] == "Intelligence V"){
		$getAffinity->bind_param("s", $blessing[0]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}
}

if($char['affinitys'] >= '2'){
	if($blessing[1] == "Might"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[1] == "Speed"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[1] == "Constitution"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[1] == "Concentration"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[1] == "Intelligence"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[1] == "Might II"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[1] == "Speed II"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[1] == "Constitution II"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[1] == "Concentration II"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[1] == "Intelligence II"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[1] == "Might III"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[1] == "Speed III"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[1] == "Constitution III"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[1] == "Concentration III"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[1] == "Intelligence III"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[1] == "Might IV"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[1] == "Speed IV"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[1] == "Constitution IV"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[1] == "Concentration IV"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[1] == "Intelligence IV"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[1] == "Might V"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[1] == "Speed V"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[1] == "Constitution V"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[1] == "Concentration V"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[1] == "Intelligence V"){
		$getAffinity->bind_param("s", $blessing[1]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}
}

if($char['affinitys'] >= '3')
{
	if($blessing[2] == "Might"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[2] == "Speed"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[2] == "Constitution"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[2] == "Concentration"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[2] == "Intelligence"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[2] == "Might II"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[2] == "Speed II"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[2] == "Constitution II"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[2] == "Concentration II"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[2] == "Intelligence II"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[2] == "Might III"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[2] == "Speed III"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[2] == "Constitution III"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[2] == "Concentration III"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[2] == "Intelligence III"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[2] == "Might IV"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[2] == "Speed IV"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[2] == "Constitution IV"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[2] == "Concentration IV"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[2] == "Intelligence IV"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[2] == "Might V"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[2] == "Speed V"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[2] == "Constitution V"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[2] == "Concentration V"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[2] == "Intelligence V"){
		$getAffinity->bind_param("s", $blessing[2]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}
}

if($char['affinitys'] >= '4'){
	if($blessing[3] == "Might"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[3] == "Speed"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[3] == "Constitution"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[3] == "Concentration"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[3] == "Intelligence"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[3] == "Might II"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[3] == "Speed II"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[3] == "Constitution II"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[3] == "Concentration II"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[3] == "Intelligence II"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[3] == "Might III"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[3] == "Speed III"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[3] == "Constitution III"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[3] == "Concentration III"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[3] == "Intelligence III"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[3] == "Might IV"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[3] == "Speed IV"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[3] == "Constitution IV"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[3] == "Concentration IV"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[3] == "Intelligence IV"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[3] == "Might V"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[3] == "Speed V"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[3] == "Constitution V"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[3] == "Concentration V"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[3] == "Intelligence V"){
		$getAffinity->bind_param("s", $blessing[3]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}
}

if($char['affinitys'] >= '5'){
	if($blessing[4] == "Might"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[4] == "Speed"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[4] == "Constitution"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[4] == "Concentration"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[4] == "Intelligence"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[4] == "Might II"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[4] == "Speed II"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[4] == "Constitution II"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[4] == "Concentration II"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[4] == "Intelligence II"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[4] == "Might III"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[4] == "Speed III"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[4] == "Constitution III"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[4] == "Concentration III"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[4] == "Intelligence III"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[4] == "Might IV"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[4] == "Speed IV"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[4] == "Constitution IV"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[4] == "Concentration IV"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[4] == "Intelligence IV"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[4] == "Might V"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[4] == "Speed V"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[4] == "Constitution V"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[4] == "Concentration V"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[4] == "Intelligence V"){
		$getAffinity->bind_param("s", $blessing[4]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}
}

if($char['affinitys'] >= '6'){
	if($blessing[5] == "Might"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[5] == "Speed"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[5] == "Constitution"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[5] == "Concentration"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[5] == "Intelligence"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[5] == "Might II"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[5] == "Speed II"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[5] == "Constitution II"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[5] == "Concentration II"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[5] == "Intelligence II"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[5] == "Might III"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[5] == "Speed III"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[5] == "Constitution III"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[5] == "Concentration III"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[5] == "Intelligence III"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[5] == "Might IV"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[5] == "Speed IV"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[5] == "Constitution IV"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[5] == "Concentration IV"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[5] == "Intelligence IV"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[5] == "Might V"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[5] == "Speed V"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[5] == "Constitution V"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[5] == "Concentration V"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[5] == "Intelligence V"){
		$getAffinity->bind_param("s", $blessing[5]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}
}

if($char['affinitys'] >= '7'){
	if($blessing[6] == "Might"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[6] == "Speed"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[6] == "Constitution"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[6] == "Concentration"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[6] == "Intelligence"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[6] == "Might II"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[6] == "Speed II"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[6] == "Constitution II"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[6] == "Concentration II"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[6] == "Intelligence II"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[6] == "Might III"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[6] == "Speed III"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[6] == "Constitution III"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[6] == "Concentration III"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[6] == "Intelligence III"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[6] == "Might IV"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[6] == "Speed IV"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[6] == "Constitution IV"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[6] == "Concentration IV"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[6] == "Intelligence IV"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[6] == "Might V"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[6] == "Speed V"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[6] == "Constitution V"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[6] == "Concentration V"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[6] == "Intelligence V"){
		$getAffinity->bind_param("s", $blessing[6]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}
}

if($char['affinitys'] >= '8'){
	if($blessing[7] == "Might"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[7] == "Speed"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[7] == "Constitution"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[7] == "Concentration"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[7] == "Intelligence"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[7] == "Might II"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[7] == "Speed II"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[7] == "Constitution II"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[7] == "Concentration II"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[7] == "Intelligence II"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[7] == "Might III"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[7] == "Speed III"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[7] == "Constitution III"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[7] == "Concentration III"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[7] == "Intelligence III"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[7] == "Might IV"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[7] == "Speed IV"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[7] == "Constitution IV"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[7] == "Concentration IV"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[7] == "Intelligence IV"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[7] == "Might V"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[7] == "Speed V"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[7] == "Constitution V"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[7] == "Concentration V"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[7] == "Intelligence V"){
		$getAffinity->bind_param("s", $blessing[7]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}
}

if($char['affinitys'] >= '9'){
	if($blessing[8] == "Might"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[8] == "Speed"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[8] == "Constitution"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[8] == "Concentration"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[8] == "Intelligence"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[8] == "Might II"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[8] == "Speed II"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[8] == "Constitution II"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[8] == "Concentration II"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[8] == "Intelligence II"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[8] == "Might III"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[8] == "Speed III"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[8] == "Constitution III"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[8] == "Concentration III"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[8] == "Intelligence III"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[8] == "Might IV"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[8] == "Speed IV"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[8] == "Constitution IV"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[8] == "Concentration IV"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[8] == "Intelligence IV"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[8] == "Might V"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charstr *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[8] == "Speed V"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$chardex *= ("1" + ($level['level'] / "10"));
	}

	if($blessing[8] == "Constitution V"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charend *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[8] == "Concentration V"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charcon *= ("1" + ($level['level'] / "10"));
	}
	
	if($blessing[8] == "Intelligence V"){
		$getAffinity->bind_param("s", $blessing[8]);
		$getAffinity->execute();
		$level = $getAffinity->get_result()->fetch_assoc();
		$charint *= ("1" + ($level['level'] / "10"));
	}
}
?>