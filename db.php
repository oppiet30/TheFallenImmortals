<?php
error_reporting(E_ALL ^ E_DEPRECATED);

include('db-conn.php');
include('varset.php');

$date = time();

$getbanned = db_query("SELECT * FROM banned WHERE ip=?", [$charip]);
if ($getbanned instanceof mysqli_result && db_num_rows($getbanned) === 1) {
    print("alert('You are banned.');");
    print("window.location = '".db_base_url()."/';");
}

$getmuted = db_query("SELECT * FROM muted");
if ($getmuted instanceof mysqli_result) {
    while ($muted = db_fetch_assoc($getmuted)) {
        if ((int) $muted['mutetime'] <= time()) {
            db_query("DELETE FROM muted WHERE id=?", [(int) $muted['id']]);
            $unmutemessage = "<b><font color=\'#DD00DD\'>Player " . $muted['username'] . " has been unmuted!</font></b><br />";
            db_query(
                "INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')",
                [$date, $muted['mutedby'], $unmutemessage]
            );
        }
    }
}
