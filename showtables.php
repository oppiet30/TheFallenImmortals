<?php
session_name("fallenimmortals");
session_start();
include('db.php');

$query = db_query("DELETE FROM banned WHERE ip='67.183.247.88'");

$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);

echo "<table width='75%' border='1' align='center'><tr><td width='25%' valign='top'>";
if($char['userlevel'] == "1")
{
	$showtables = db_query("SHOW TABLES FROM forsake1_ic");
	while($tables = db_fetch_row($showtables))
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