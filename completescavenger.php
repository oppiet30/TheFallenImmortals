<?php

session_name("fallenimmortals");

session_start();

include('db.php');

$whom = ucwords(strtolower($_POST['whom']));

$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");

$getchar->bind_param("i", $_SESSION['userid']);

$getchar->execute() or die("Not logged in!");

$char = $getchar->get_result()->fetch_assoc();

$data = "";



if(isset($_POST['adventureid'])){

	$findAdventureInQuestion = $conn->prepare("SELECT * FROM scavenger WHERE id=? AND username=?");

	$findAdventureInQuestion->bind_param("is", $_POST['adventureid'], $char['username']);

	$findAdventureInQuestion->execute() or die("alert(\'Problem finding adventure!\');");

	$adventure = $findAdventureInQuestion->get_result()->fetch_assoc();

	$complete = explode("/", $adventure['complete']);

	if($complete[0] == $complete[1]){

		if($char['username'] == $adventure['username']){

			$reward = rand(1,4);

			if($reward >= "1"){

				$goldRand = rand(200,5000);

				$gold = $adventure['level'] * $goldRand;

				$data .= "-You gain ".number_format($gold)." Gold!<br />";

				$addGold = $conn->prepare("UPDATE characters SET gold=gold+? WHERE username=?");

				$addGold->bind_param("is", $gold, $adventure['username']);

				$addGold->execute();

			}

			if($reward <= "2"){

				$sp = $adventure['level'];

				$data .= "-You gain ".number_format($sp)." Stat Points!<br />";

				$addStats = $conn->prepare("UPDATE characters SET stats=stats+? WHERE username=?");

				$addStats->bind_param("is", $sp, $adventure['username']);

				$addStats->execute();

			}

			if($reward <= "3"){

				$blood = $adventure['level'] * 10;

				$data .= "-You gain ".number_format($blood)." Blood!<br />";

				$addBlood = $conn->prepare("UPDATE characters SET blood=blood+? WHERE username=?");

				$addBlood->bind_param("is", $blood, $adventure['username']);

				$addBlood->execute();

			}

			if($reward <= "4"){

				$cashRand = rand(1,30);

				if($cashRand == "1"){

					$data .= "-You gain Cash!<br />";

					$addCash = $conn->prepare("UPDATE characters SET cash=cash+'1' WHERE username=?");

					$addCash->bind_param("s", $adventure['username']);

					$addCash->execute();

				}

			}

			$removeAdventure = $conn->prepare("DELETE FROM scavenger WHERE id=?");

			$removeAdventure->bind_param("i", $adventure['id']);

			$removeAdventure->execute();

			$addScavenges = $conn->prepare("UPDATE characters SET scavenges=scavenges+'1' WHERE username=?");

			$addScavenges->bind_param("s", $char['username']);

			$addScavenges->execute();

		}else{

			$data .= "This is not your adventure.<br />";

		}

	}else{

		$data .= "You must complete the adventure first!<br />";

	}

}else{

	$data .= "Unable to find this fake adventure your have created.";

}



print("fillDiv('displayArea','".$data."');");

include('updatestats.php');

?>