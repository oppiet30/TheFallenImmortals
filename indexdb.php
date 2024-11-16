<?php
error_reporting(E_ALL ^ E_DEPRECATED);
//I've set this up to make the connection to your db, shouldn't need changing unless i got something wrong
$dbhost = "localhost";
$database = "homestead";
$dbuser = "homestead";
$dbpass = "secret";

$login = mysqli_connect($dbhost, $dbuser, $dbpass) or trigger_error(mysqli_error(),E_USER_ERROR);
mysqli_select_db($database) or die("Where?");



//Global Variables
$date = time();
?>