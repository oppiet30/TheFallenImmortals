<?php
error_reporting(E_ALL ^ E_DEPRECATED);
//I've set this up to make the connection to your db, shouldn't need changing unless i got something wrong
include_once "includes/db.inc.php";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $database) or trigger_error(mysqli_error(mysqli_connect($dbhost, $dbuser, $dbpass, $database)),E_USER_ERROR);
// mysqli_select_db($database) or die("Where?");



//Global Variables
$date = time();
?>