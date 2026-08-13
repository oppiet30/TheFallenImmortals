<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include('varset.php');

$getguild = $conn->prepare("SELECT * FROM guilds WHERE name=?");
$getguild->bind_param("s", $charguild);
$getguild->execute();
$guild = $getguild->get_result()->fetch_assoc();
if($charname == $guild['leader'] || $charname == $guild['coleader'])
{
	if($_POST['modify'] == "recruit")
	{
		if($guild['recruiting'] == "Yes")
		{
			$set = "No";
		}
		else
		{
			$set = "Yes";
		}
		$setguild = $conn->prepare("UPDATE guilds SET recruiting=? WHERE name=?");
		$setguild->bind_param("ss", $set, $char['guild']);
		$setguild->execute();
	}
	elseif($_POST['modify'] == "accept")
	{		if($guild['accept'] == "Approve")
		{
			$set = "Auto";
		}
		else
		{
			$set = "Approve";
		}
		$setguild = $conn->prepare("UPDATE guilds SET accept=? WHERE name=?");
		$setguild->bind_param("ss", $set, $charguild);
		$setguild->execute();
	}
	elseif($_POST['modify'] == "applicant")
	{
		if($charname == $guild['leader'] || $charname == $guild['coleader'] || $charname == $guild['captain'])
		{
			$username = $_POST['username'];
			$getuser = $conn->prepare("SELECT * FROM characters WHERE username=? AND guild='None'");
			$getuser->bind_param("s", $username);
			$getuser->execute();
			if($getuser->get_result()->num_rows == "1")
			{
				$getapplication = $conn->prepare("SELECT * FROM applications WHERE username=?");
				$getapplication->bind_param("s", $username);
				$getapplication->execute();
				if($getapplication->get_result()->num_rows == "1")
				{
					$updateapplicant = $conn->prepare("UPDATE characters SET guild=? WHERE username=?");
					$updateapplicant->bind_param("ss", $guild['name'], $username);
					$updateapplicant->execute();
					$deleteapplication = $conn->prepare("DELETE FROM applications WHERE username=?");
					$deleteapplication->bind_param("s", $username);
					$deleteapplication->execute();
				}
			}
		}
	}
	elseif($_POST['modify'] == "kick")
	{
		if($charname == $guild['leader'] || $charname == $guild['coleader'])
		{
			$username = $_POST['username'];
			$getuser = $conn->prepare("SELECT * FROM characters WHERE username=? AND guild=?");
			$getuser->bind_param("ss", $username, $guild['name']);
			$getuser->execute();
			if($getuser->get_result()->num_rows == "1")
			{
				if($username != $guild['leader'])
				{
					$getmember = $conn->prepare("SELECT * FROM characters WHERE username=?");
					$getmember->bind_param("s", $username);
					$getmember->execute();
					if($getmember->get_result()->num_rows == "1")
					{
						$updateapplicant = $conn->prepare("UPDATE characters SET guild='None' WHERE username=?");
						$updateapplicant->bind_param("s", $username);
						$updateapplicant->execute();
						if($username == $guild['coleader'])
						{
							$updateguild = $conn->prepare("UPDATE guilds SET coleader='' WHERE name=?");
							$updateguild->bind_param("s", $charguild);
							$updateguild->execute();
						}
						if($username == $guild['captain'])
						{
							$updateguild = $conn->prepare("UPDATE guilds SET captain='' WHERE name=?");
							$updateguild->bind_param("s", $charguild);
							$updateguild->execute();
						}
					}
				}
			}
		}
	}
	elseif($_POST['modify'] == "promote")
	{
		$username = $_POST['username'];
		$getuser = $conn->prepare("SELECT * FROM characters WHERE username=? AND guild=?");
		$getuser->bind_param("ss", $username, $guild['name']);
		$getuser->execute();
		if($getuser->get_result()->num_rows == "1")
		{
			if($charname == $guild['leader'])
			{
				if($username != $guild['leader'])
				{
					if($username == $guild['captain'])	//Make Co-Leader
					{
						$updateguild = $conn->prepare("UPDATE guilds SET coleader=?, captain='' WHERE name=?");
						$updateguild->bind_param("ss", $username, $charguild);
						$updateguild->execute();
					}
					else	//Make Captain
					{
						$updateguild = $conn->prepare("UPDATE guilds SET captain=? WHERE name=?");
						$updateguild->bind_param("ss", $username, $charguild);
						$updateguild->execute();
					}
				}
			}
			elseif($charname == $guild['coleader'])
			{
				if($username != $guild['leader'] && $username != $guild['coleader'])
				{
					$updateguild = $conn->prepare("UPDATE guilds SET captain=? WHERE name=?");
					$updateguild->bind_param("ss", $username, $charguild);
					$updateguild->execute();
				}
			}
		}
	}
	elseif($_POST['modify'] == "demote")
	{
		$username = $_POST['username'];
		$getuser = $conn->prepare("SELECT * FROM characters WHERE username=? AND guild=?");
		$getuser->bind_param("ss", $username, $guild['name']);
		$getuser->execute();
		if($getuser->get_result()->num_rows == "1")
		{
			if($charname == $guild['leader'])
			{
				if($username != $guild['leader'])
				{
					if($username == $guild['coleader'])	//Make Captain
					{
						$updateguild = $conn->prepare("UPDATE guilds SET coleader='', captain=? WHERE name=?");
						$updateguild->bind_param("ss", $username, $charguild);
						$updateguild->execute();
					}
					else	//Make Member
					{
						$updateguild = $conn->prepare("UPDATE guilds SET captain='' WHERE name=?");
						$updateguild->bind_param("s", $charguild);
						$updateguild->execute();
					}
				}
			}
			elseif($charname == $guild['coleader'])
			{
				if($username != $guild['leader'] && $username != $guild['coleader'])
				{
					$updateguild = $conn->prepare("UPDATE guilds SET captain='' WHERE name=?");
					$updateguild->bind_param("s", $charguild);
					$updateguild->execute();
				}
			}
		}
	}
	print("viewGuild();");
}
?>