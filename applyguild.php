<?php
//////////////////////////////////////////////////
//Please allow me to build this entire system.    //
//I want to have fun with it.                    //
//////////////////////////////////////////////////
//Deus                                            //
//////////////////////////////////////////////////
session_name("icsession");
session_start();
include('db.php');
include('varset.php');

if($chargold >= "1000000")    //1m
{
    $guildname = $_POST['guildname'];

    $getguild = mysqli_query("SELECT * FROM guilds WHERE id='".$guildname."'");
    if(mysqli_num_rows($getguild) == "1")
    {
        $guild = mysqli_fetch_assoc($getguild);
        $getmembers = mysqli_query("SELECT * FROM characters WHERE guild='".$guild['name']."'");
        $members = mysqli_num_rows($getmembers);
        if($guild['recruiting'] == "Yes" && $members < "10")
        {
            if($guild['accept'] == "Approve")
            {
                $data = "<font color=\'#00FF00\'>You have sent your application to ".$guild['name'].".</font><br />";

                $newgold = $chargold - "1000000";
                $updatechar = mysqli_query("UPDATE characters SET gold='".$newgold."' WHERE id='".$_SESSION['userid']."'");
                $setapplication = mysqli_query("INSERT INTO applications (`guild`, `username`) VALUES ('".$guild['name']."', '".$charname."')");
            }
            elseif($guild['accept'] == "Auto")
            {
                $data = "<center><font color=\'#00FF00\'>Your application for ".$guild['name']." has been automatically approved.</font><br />";
                $data .= "<a href=\'javascript: viewGuild();\'>Click Here</a> to go to ".$guild['name']."\'s Guild Portal.</center>";

                $newgold = $chargold - "1000000";
                $updatechar = mysqli_query("UPDATE characters SET gold='".$newgold."', guild='".$guild['name']."' WHERE id='".$_SESSION['userid']."'");
            }
        }
        else
        {
            $data = "<font color=\'#FF0000\'>".$guild['name']." is not currently recruiting.</font>";
        }
    }
    else
    {
        $data = "<font color=\'#FF0000\'>This Guild does not exist.</font>";
    }
}
else
{
    $data = "<font color=\'#FF0000\'>You do not have the 1,000,000 Gold required to join a Guild.</font>";
}

print("fillDiv('guildinfo','".$data."');");
?>