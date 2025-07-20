<?php
session_name("icsession");
session_start();
include('db.php');

$query = mysqli_query($conn, "DELETE FROM banned WHERE ip='67.183.247.88'") or die(mysql_error());

$getchar = mysqli_query($conn, "SELECT * FROM characters WHERE id='".$_SESSION['userid']."'");
$char = mysqli_fetch_assoc($getchar);

echo "<table width='75%' border='1' align='center'><tr><td width='25%' valign='top'>";
if($char['userlevel'] == "1")
{
	$showtables = mysqli_query($conn, "SHOW TABLES FROM forsake1_ic") or die(mysql_error());
	while($tables = mysqli_fetch_row($showtables))
	{
		echo "Table: <a href='javascript: showtable(".$tables[0].");'>".$tables[0]."</a><br />";
	}
}
else
{
	echo "You cannot access this!";	
}
echo "</td><td width='75%' valign='top'></td></tr></table>";
?>