<?php
error_reporting(E_ALL ^ E_DEPRECATED);
//I've set this up to make the connection to your db, shouldn't need changing unless i got something wrong
$dbhost = "localhost";
$database = "fallendb";
$dbuser = "fallen";
$dbpass = "19KiNg73";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $database) or trigger_error(mysqli_connect_error(),E_USER_ERROR);



//Global Variables
$date = time();
?>