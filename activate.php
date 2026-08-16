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
	$findkey = $conn->prepare("SELECT * FROM activation WHERE `key`=?");
	$findkey->bind_param("s", $_GET['key']);
	$findkey->execute() or die($conn->error);
	$findkeyResult = $findkey->get_result();
	if($findkeyResult->num_rows == "1")
	{
		$key = $findkeyResult->fetch_assoc();
		echo("<center>The account, ".$key['username']." has now been activated. You may now start playing immediately.<br /><a href='index.php'>Login Here</a></center>");

		$deletekey = $conn->prepare("DELETE FROM activation WHERE `key`=?");
		$deletekey->bind_param("s", $_GET['key']);
		$deletekey->execute() or die($conn->error);
		$activatechar = $conn->prepare("UPDATE characters SET activated='Yes' WHERE username=?");
		$activatechar->bind_param("s", $key['username']);
		$activatechar->execute();
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
