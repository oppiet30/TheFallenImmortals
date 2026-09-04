<?php
    include('db.php');
    session_name("icsession");
    session_start();
    $time = time() - "1000";
    $findonline = db_query("UPDATE characters SET lastactive=? WHERE id=?", [$time, $_SESSION['userid']]);
    $display = "Logging you out...";
    print("fillDiv('displayArea','".$display."');");
    session_unset(); 
    print("window.location = '".db_base_url()."/';");
?>