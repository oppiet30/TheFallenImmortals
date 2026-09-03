<?php
error_reporting(E_ALL ^ E_DEPRECATED);

require_once __DIR__ . '/src/helpers.php';

$dbhost = "localhost";
$database = "fallendb";
$dbuser = "fallen";
$dbpass = "YOUR_DB_PASSWORD";

db_connect($dbhost, $dbuser, $dbpass, $database);
