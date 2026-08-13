<?php
session_name("fallenimmortals");
session_start();
include('db.php');

$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute() or die($conn->error);
$char = $getchar->get_result()->fetch_assoc();

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