<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include('varset.php');

$getguild = db_query("SELECT * FROM guilds WHERE name=?", [$charguild]);
$guild = db_fetch_assoc($getguild);
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
		$setguild = db_query("UPDATE guilds SET recruiting=? WHERE name=?", [$set, $char['guild']]);
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
		$setguild = db_query("UPDATE guilds SET accept=? WHERE name=?", [$set, $charguild]);
	}
	elseif($_POST['modify'] == "applicant")
	{
		if($charname == $guild['leader'] || $charname == $guild['coleader'] || $charname == $guild['captain'])
		{
			$username = $_POST['username'];
			$getuser = db_query("SELECT * FROM characters WHERE username=? AND guild='None'", [$username]);
			if(db_num_rows($getuser) == "1")
			{
				$getapplication = db_query("SELECT * FROM applications WHERE username=?", [$username]);
				if(db_num_rows($getapplication) == "1")
				{
					$updateapplicant = db_query("UPDATE characters SET guild=? WHERE username=?", [$guild['name'], $username]);
					$deleteapplication = db_query("DELETE FROM applications WHERE username=?", [$username]);
				}
			}
		}
	}
	elseif($_POST['modify'] == "kick")
	{
		if($charname == $guild['leader'] || $charname == $guild['coleader'])
		{
			$username = $_POST['username'];
			$getuser = db_query("SELECT * FROM characters WHERE username=? AND guild=?", [$username, $guild['name']]);
			if(db_num_rows($getuser) == "1")
			{
				if($username != $guild['leader'])
				{
					$getmember = db_query("SELECT * FROM characters WHERE username=?", [$username]);
					if(db_num_rows($getmember) == "1")
					{
						$updateapplicant = db_query("UPDATE characters SET guild='None' WHERE username=?", [$username]);
						if($username == $guild['coleader'])
						{
							$updateguild = db_query("UPDATE guilds SET coleader='' WHERE name=?", [$charguild]);
						}
						if($username == $guild['captain'])
						{
							$updateguild = db_query("UPDATE guilds SET captain='' WHERE name=?", [$charguild]);
						}
					}
				}
			}
		}
	}
	elseif($_POST['modify'] == "promote")
	{
		$username = $_POST['username'];
		$getuser = db_query("SELECT * FROM characters WHERE username=? AND guild=?", [$username, $guild['name']]);
		if(db_num_rows($getuser) == "1")
		{
			if($charname == $guild['leader'])
			{
				if($username != $guild['leader'])
				{
					if($username == $guild['captain'])	//Make Co-Leader
					{
						$updateguild = db_query("UPDATE guilds SET coleader=?, captain='' WHERE name=?", [$username, $charguild]);
					}
					else	//Make Captain
					{
						$updateguild = db_query("UPDATE guilds SET captain=? WHERE name=?", [$username, $charguild]);
					}
				}
			}
			elseif($charname == $guild['coleader'])
			{
				if($username != $guild['leader'] && $username != $guild['coleader'])
				{
					$updateguild = db_query("UPDATE guilds SET captain=? WHERE name=?", [$username, $charguild]);
				}
			}
		}
	}
	elseif($_POST['modify'] == "demote")
	{
		$username = $_POST['username'];
		$getuser = db_query("SELECT * FROM characters WHERE username=? AND guild=?", [$username, $guild['name']]);
		if(db_num_rows($getuser) == "1")
		{
			if($charname == $guild['leader'])
			{
				if($username != $guild['leader'])
				{
					if($username == $guild['coleader'])	//Make Captain
					{
						$updateguild = db_query("UPDATE guilds SET coleader='', captain=? WHERE name=?", [$username, $charguild]);
					}
					else	//Make Member
					{
						$updateguild = db_query("UPDATE guilds SET captain='' WHERE name=?", [$charguild]);
					}
				}
			}
			elseif($charname == $guild['coleader'])
			{
				if($username != $guild['leader'] && $username != $guild['coleader'])
				{
					$updateguild = db_query("UPDATE guilds SET captain='' WHERE name=?", [$charguild]);
				}
			}
		}
	}
	print("viewGuild();");
}
?>