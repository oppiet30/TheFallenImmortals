<?php
    session_name("fallenimmortals");
    session_start();
    include('db-conn.php');
    $date = time();
    $time = $date - "1000";
    $getchar = mysqli_query($conn, "SELECT * FROM characters WHERE id='".$_SESSION['userid']."'");
    $char = mysqli_fetch_assoc($getchar);
    $findonline = mysqli_query($conn, "UPDATE characters SET lastactive='".$time."' WHERE id='".$_SESSION['userid']."'");
    if($char){
        $messagechat = "<strong><font color=\'#999999\'>".$char['username']." has logged out.</font></strong><br />";
        $query = mysqli_query($conn, "INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES ('".$date."', '3', '".$char['username']."', '".$messagechat."', 'Chatroom')");
    }
    $display = "Logging you out...";
    print("fillDiv('displayArea','".$display."');");
    session_unset();
    session_destroy();
    print("window.location = 'index.php';");
?>