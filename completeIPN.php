<?php
session_name("fallenimmortals");
session_start();
include('db.php');

$getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
$getchar->bind_param("i", $_SESSION['userid']);
$getchar->execute() or die($conn->error);
$char = $getchar->get_result()->fetch_assoc();

// PHP 4.1
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

foreach($_POST as $key => $value)
{
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
}

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

// assign posted variables to local variables
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];

if(!$fp){
	// HTTP ERROR
}
else
{
	fputs($fp, $header . $req);
		while(!feof($fp))
		{
			$res = fgets ($fp, 1024);
			if(strcmp ($res, "VERIFIED") == 0)
			{
				if($payment_status == "Completed" && $receiver_email == "Alex.Jezior@gmail.com"){
				$getchar = $conn->prepare("SELECT * FROM characters WHERE email=?");
				$getchar->bind_param("s", $payer_email);
				$getchar->execute() or die($conn->error);
				$payer = $getchar->get_result()->fetch_assoc();
				if($payment_amount == "5.25" && $payment_currency == "USD")
				{
					$netAmount = '5';
					$cashAmount = floor('5' * "1.2");
					$updatePlayer = $conn->prepare("UPDATE characters SET networth=networth+?, cash=cash+? WHERE email=?");
					$updatePlayer->bind_param("iis", $netAmount, $cashAmount, $payer_email);
					$updatePlayer->execute();
					$messagechat = "<strong><font color=\'#9933FF\'><b>".$payer['username']."</b> just made a purchase from the Purchase page, for the amount of ".$cashAmount." game cash!<br />Congratulations on all your success!</font></strong><br />";
					$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
					$query->bind_param("iss", $date, $payer['username'], $messagechat);
					$query->execute();
				}
				elseif($payment_amount == "10.50" && $payment_currency == "USD")
				{
					$netAmount = '10';
					$cashAmount = floor('11' * "1.2");
					$updatePlayer = $conn->prepare("UPDATE characters SET networth=networth+?, cash=cash+? WHERE email=?");
					$updatePlayer->bind_param("iis", $netAmount, $cashAmount, $payer_email);
					$updatePlayer->execute();
					$messagechat = "<strong><font color=\'#9933FF\'><b>".$payer['username']."</b> just made a purchase from the Purchase page, for the amount of ".$cashAmount." game cash!<br />Congratulations on all your success!</font></strong><br />";
					$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
					$query->bind_param("iss", $date, $payer['username'], $messagechat);
					$query->execute();
				}
				elseif($payment_amount == "21.00" && $payment_currency == "USD")
				{
					$netAmount = '20';
					$cashAmount = floor('23' * "1.2");
					$updatePlayer = $conn->prepare("UPDATE characters SET networth=networth+?, cash=cash+? WHERE email=?");
					$updatePlayer->bind_param("iis", $netAmount, $cashAmount, $payer_email);
					$updatePlayer->execute();
					$messagechat = "<strong><font color=\'#9933FF\'><b>".$payer['username']."</b> just made a purchase from the Purchase page, for the amount of ".$cashAmount." game cash!<br />Congratulations on all your success!</font></strong><br />";
					$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
					$query->bind_param("iss", $date, $payer['username'], $messagechat);
					$query->execute();
				}
				elseif($payment_amount == "52.50" && $payment_currency == "USD")
				{
					$netAmount = '50';
					$cashAmount = floor('58' * "1.2");
					$updatePlayer = $conn->prepare("UPDATE characters SET networth=networth+?, cash=cash+? WHERE email=?");
					$updatePlayer->bind_param("iis", $netAmount, $cashAmount, $payer_email);
					$updatePlayer->execute();
					$messagechat = "<strong><font color=\'#9933FF\'><b>".$payer['username']."</b> just made a purchase from the Purchase page, for the amount of ".$cashAmount." game cash!<br />Congratulations on all your success!</font></strong><br />";
					$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
					$query->bind_param("iss", $date, $payer['username'], $messagechat);
					$query->execute();
				}
				elseif($payment_amount == "105.00" && $payment_currency == "USD")
				{
					$netAmount = '100';
					$cashAmount = floor('120' * "1.2");
					$updatePlayer = $conn->prepare("UPDATE characters SET networth=networth+?, cash=cash+? WHERE email=?");
					$updatePlayer->bind_param("iis", $netAmount, $cashAmount, $payer_email);
					$updatePlayer->execute();
					$messagechat = "<strong><font color=\'#9933FF\'><b>".$payer['username']."</b> just made a purchase from the Purchase page, for the amount of ".$cashAmount." game cash!<br />Congratulations on all your success!</font></strong><br />";
					$query = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
					$query->bind_param("iss", $date, $payer['username'], $messagechat);
					$query->execute();
				}
			}
		// check that txn_id has not been previously processed
		}
		elseif(strcmp($res, "INVALID") == 0)
		{
			// log for manual investigation
		}
	}
	fclose ($fp);
}
?>