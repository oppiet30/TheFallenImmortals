<?php
error_reporting(E_ALL ^ E_DEPRECATED);
//I've set this up to make the connection to your db, shouldn't need changing unless i got something wrong
include db_include.php;

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $database) or trigger_error(mysqli_error($conn),E_USER_ERROR);
mysqli_select_db($conn, $database) or die("Where?");

//Global Variables
$date = time();
?>