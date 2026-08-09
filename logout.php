<?php
    session_name("fallenimmortals");
    session_start();
    include('db-conn.php');
    $time = time() - "1000";
    $findonline = mysqli_query($conn, "UPDATE characters SET lastactive='".$time."' WHERE id='".$_SESSION['userid']."'");
    $display = "Logging you out...";
    print("fillDiv('displayArea','".$display."');");
    session_unset();
    session_destroy();
    print("window.location = 'index.php';");
?>