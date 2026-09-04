<?php
    session_name("fallenimmortals");
    session_start();
    include('db.php');
    $time = time() - "1000";
    $findonline = db_query("UPDATE characters SET lastactive=? WHERE id=?", [$time, $_SESSION['userid']]);
    $display = "Logging you out...";
    print("fillDiv('displayArea','".$display."');");
    session_unset();
    session_destroy();
    setcookie(session_name(), "", time() - 42000, "/");
    print("setTimeout(\"window.location = '".db_base_url()."/index.php';\", 3500);");
?>