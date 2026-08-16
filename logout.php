<?php
    session_name("fallenimmortals");
    session_start();
    include('db-conn.php');
    $date = time();
    $time = $date - "1000";
    $getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
    $getchar->bind_param("i", $_SESSION['userid']);
    $getchar->execute();
    $char = $getchar->get_result()->fetch_assoc();
    $findonline = $conn->prepare("UPDATE characters SET lastactive=? WHERE id=?");
    $findonline->bind_param("ii", $time, $_SESSION['userid']);
    $findonline->execute();
    if($char){
        $messagechat = "<strong><font color=\'#999999\'>".$char['username']." has logged out.</font></strong><br />";
        $query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
        $query->bind_param("iss", $date, $char['username'], $messagechat);
        $query->execute();
    }
    $display = "Logging you out...";
    print("fillDiv('displayArea','".$display."');");
    session_unset();
    session_destroy();
    print("window.location = 'index.php';");
?>