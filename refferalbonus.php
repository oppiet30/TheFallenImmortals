<?php
session_name("fallenimmortals");
session_start();
include('db.php');

$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);

if($char['refferal'] != "None" && $char['refferal'] != "" && $char['refferal'] != NULL){
    $whorefferal = db_query("SELECT * FROM characters WHERE username=?", [$char['refferal']]);
    $refferal = db_fetch_assoc($whorefferal);
    
    if($newlvl == "100"){
        $bonusGold = "10000";
        $refferalNEWgold = $refferal['gold'] + $bonusGold;
        $updateRefferal = db_query("UPDATE characters SET gold=? WHERE username=?", [$refferalNEWgold, $char['refferal']]);
        $messagechat = "<strong><font color=\'#662200\'>".$char['username']." reached level ".number_format($newlvl).". As a refferal bonus, ".$refferal['username']." gets ".number_format($bonusGold)." gold!</font></strong><br />";
        $query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')", [$date, $char['username'], $messagechat]);
    }elseif($newlvl == "500"){
        $bonusGold = "100000";
        $refferalNEWgold = $refferal['gold'] + $bonusGold;
        $updateRefferal = db_query("UPDATE characters SET gold=? WHERE username=?", [$refferalNEWgold, $char['refferal']]);
        $messagechat = "<strong><font color=\'#662200\'>".$char['username']." reached level ".number_format($newlvl).". As a refferal bonus, ".$refferal['username']." gets ".number_format($bonusGold)." gold!</font></strong><br />";
        $query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')", [$date, $char['username'], $messagechat]);
    }elseif($newlvl == "1000"){
        $bonusGold = "1000000";
        $refferalNEWgold = $refferal['gold'] + $bonusGold;
        $updateRefferal = db_query("UPDATE characters SET gold=? WHERE username=?", [$refferalNEWgold, $char['refferal']]);
        $messagechat = "<strong><font color=\'#662200\'>".$char['username']." reached level ".number_format($newlvl).". As a refferal bonus, ".$refferal['username']." gets ".number_format($bonusGold)." gold!</font></strong><br />";
        $query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')", [$date, $char['username'], $messagechat]);
    }elseif($newlvl == "5000"){
        $bonusGold = "10000000";
        $refferalNEWgold = $refferal['gold'] + $bonusGold;
        $updateRefferal = db_query("UPDATE characters SET gold=? WHERE username=?", [$refferalNEWgold, $char['refferal']]);
        $messagechat = "<strong><font color=\'#662200\'>".$char['username']." reached level ".number_format($newlvl).". As a refferal bonus, ".$refferal['username']." gets ".number_format($bonusGold)." gold!</font></strong><br />";
        $query = db_query("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')", [$date, $char['username'], $messagechat]);
    }
}
?>