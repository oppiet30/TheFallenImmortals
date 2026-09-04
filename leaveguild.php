<?php
session_name("fallenimmortals");
session_start();
include('db.php');
include('varset.php');

$getguild = db_query("SELECT * FROM guilds WHERE name=?", [$charguild]);
$guild = db_fetch_assoc($getguild);

if(isset($_POST['decision']) && $_POST['decision'] == "Yes"){
    $guildname = $guild['name'];
    if($char['username'] == $guild['leader']){
        if($guild['coleader'] != Null){
            $data .= "You have left your guild promoting ".$guild['coleader']." to leader.<br />";
            $promoteColeader = db_query("UPDATE guilds SET leader=?, coleader='' WHERE name=?", [$guild['coleader'], $guild['name']]);
        }elseif($guild['captain'] != Null){
            $data .= "You have left your guild promoting ".$guild['captain']." to leader.<br />";
            $promoteCaptain = db_query("UPDATE guilds SET leader=?, captain='' WHERE name=?", [$guild['captain'], $guild['name']]);
        }else{
            $guildname = $guild['name'];
            $data .= "You have left your guild removing it from the game.<br />";
            $messagechat = "<strong><font color=\'#CCFF00\'>".$char['username']." has left the guild ".$guildname.". With no one as Co-leader or Captain the guild was destroyed.</font></strong><br />";
            $query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES ('', '3', ?, ?, 'Chatroom')", [$char['username'], $messagechat]);
            $removeUsers = db_query("UPDATE characters SET guild='None', totaldonations='0' WHERE guild=?", [$charguild]);
            $goodbyeGuild = db_query("DELETE FROM guilds WHERE name=?", [$guildname]);
            $goodbyeLog = db_query("DELETE FROM log WHERE name=?", [$guildname]);
        }
    }elseif($char['username'] == $guild['coleader']){
        $data .= "You have left your guild.<br />";
        $promoteCaptain = db_query("UPDATE guilds SET coleader='' WHERE name=?", [$guild['name']]);
    }elseif($char['username'] == $guild['captain']){
        $data .= "You have left your guild.<br />";
        $promoteCaptain = db_query("UPDATE guilds SET captain='' WHERE name=?", [$guild['name']]);
    }
    $logMessage = "".$char['username']." left the guild!";
    $letGuildKnow = db_query("INSERT INTO log (`name`, `message`) VALUES (?, ?)", [$charguild, $logMessage]);
    $updateCharacter = db_query("UPDATE characters SET guild='None', totaldonations='0' WHERE username=?", [$char['username']]);
    $data .= "There was a successful get away from your guild!<br />";
}elseif(isset($_POST['decision']) && $_POST['decision'] == "No"){
    $data .= "Your guild will be pleased with this decision.<br />";
    print"viewGuild();";
}else{
    $data .= "<strong>Are you sure you want to leave your guild?</strong><br />You will recieve no compensation once you leave the guild.<br />This is your final decision...<br /><a href=\'javascript: confirmLeave(\"Yes\");\'>Yes</a> | <a href=\'javascript: confirmLeave(\"No\");\'>No</a><br />";
}
print("fillDiv('displayArea','".$data."');");
include('updatestats.php');
?>