<?php
include('db.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1250" />
<title>Rise Of Immortals ~ Activation</title>
<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
if($_GET['key'] != "")
{
	$findkey = mysqli_query($conn, "SELECT * FROM activation WHERE `key`='".$_GET['key']."'") or die(mysqli_error($conn));
	if(mysqli_num_rows($findkey) == "1")
	{
		$key = mysqli_fetch_assoc($findkey);
		echo("<center>The account, ".$key['username']." has now been activated. You may now start playing immediately.<br /><a href='index.php'>Login Here</a></center>");

		$deletekey = mysqli_query($conn, "DELETE FROM activation WHERE `key`='".$_GET['key']."'") or die(mysqli_error($conn));
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
