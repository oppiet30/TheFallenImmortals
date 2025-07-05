<?php
session_name("icsession");
session_start();
include('db.php');

$getchar = mysqli_query($conn, "SELECT * FROM characters WHERE id='".$_SESSION['userid']."'") or die(mysqli_error());
$char = mysqli_fetch_assoc($getchar);

if($char['username'] == "Ajezior" || $char['username'] == "Wtfheather"){
	print "<table>";
	$getmessages = mysqli_query($conn, "SELECT * FROM chatroom ORDER BY id DESC LIMIT 2500");
	while($messages = mysqli_fetch_array($getmessages))
    {
        $username = $messages['username'];
        $userlevel = $messages['userlevel'];
        $to = $messages['to'];
        $date = $messages['date'];
        $message = str_replace("+", "\+", $messages['message']);
        $message = str_replace("&", "\&", $messages['message']);
        $message = str_replace("'", "\'", $message);
        $message = str_replace("\\\\", "\\", $message);
    
        $data = $data.$message;
    }
    print "<tr><td>".$data."</td></tr>";
    print "</table>";
}else{
	print "You should not be here.";
}
?>