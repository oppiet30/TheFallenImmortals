<?php
include('db.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1250" />
<title>Rise Of Immortals ~ Activation</title>
<link href="main.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
if($_GET['key'] != "")
{
	$findkey = mysqli_query($conn, "SELECT * FROM activation WHERE `key`='".$_GET['key']."'") or die(mysql_error());
	if(mysql_num_rows($findkey) == "1")
	{
		$key = mysql_fetch_assoc($findkey);
		echo("<center>The account, ".$key['username']." has now been activated. You may now start playing immediately.<br /><a href='index.php'>Login Here</a></center>");

		$deletekey = mysqli_query($conn, "DELETE FROM activation WHERE `key`='".$_GET['key']."'") or die(mysql_error());
		$activatechar = mysqli_query($conn, "UPDATE characters SET activated='Yes' WHERE username='".$key['username']."'");
	}
	else
	{
		echo("<center>This Activation Key cannot be found. Please follow the link sent to your email address.</center>");
	}
}
else
{
	echo("<center>Cannot find Activation Key. Please follow the link sent to your email address.</center>");
}
?>
</body>
</html>
