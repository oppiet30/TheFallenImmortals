<?php
// Redirect target after the buyer approves payment on PayPal.
// Captures the order and, if the webhook did not already credit the account,
// credits cash immediately so the buyer sees the result without waiting.

session_name("fallenimmortals");
session_start();
include('db.php');

require_once __DIR__ . '/src/PayPal.php';

$tierKey = (string)($_GET['tier'] ?? '');
$orderId = (string)($_GET['token'] ?? '');
$payerId = (string)($_GET['PayerID'] ?? '');

$tiers = [
    'FIVE_CASH'       => ['price' => '5.25',  'cash' => 5,   'title' => '5 Cash'],
    'TEN_CASH'        => ['price' => '10.50', 'cash' => 11,  'title' => '11 Cash'],
    'TWENTY_CASH'     => ['price' => '21.00', 'cash' => 23,  'title' => '23 Cash'],
    'FIFTY_CASH'      => ['price' => '52.50', 'cash' => 58,  'title' => '58 Cash'],
    'ONEHUNDRED_CASH' => ['price' => '105.00', 'cash' => 120, 'title' => '120 Cash'],
];

$tier = $tiers[$tierKey] ?? null;
$resultMessage = 'Payment could not be completed. Please try again or contact support.';

if ($tier !== null && $orderId !== '') {
    try {
        $paypal = new PayPal();
        $capture = $paypal->captureOrder($orderId);

        if ($capture !== null) {
            // Successful new capture. Credit if not already credited by webhook.
            $txnId = $capture['purchase_units'][0]['payments']['captures'][0]['id'] ?? '';
            $already = false;
            if ($txnId !== '') {
                $check = db_query('SELECT id FROM `log` WHERE message LIKE ?', ['%' . $txnId . '%']);
                $already = db_num_rows($check) > 0;
            }

            if (!$already) {
                $getchar = db_query('SELECT username FROM characters WHERE id=?', [$_SESSION['userid']]);
                $char = db_fetch_assoc($getchar);
                $username = $char['username'] ?? '';

                if ($username !== '') {
                    db_query(
                        'UPDATE characters SET networth = networth + ?, cash = cash + ? WHERE username = ?',
                        [(int)$tier['cash'], (int)$tier['cash'], $username]
                    );

                    $message = "<b><font color=\\'#9933FF\\'>" . $username . ' just made a purchase for '
                        . $tier['cash'] . ' game cash!<br />Congratulations on all your success!</font></b><br />';
                    db_query(
                        "INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')",
                        [time(), $username, $message]
                    );

                    db_query(
                        'INSERT INTO `log` (`name`, `message`) VALUES (?, ?)',
                        [$username, 'PayPal txn ' . $txnId . ' (capture)']
                    );
                }
            }

            $resultMessage = 'Thank you! ' . $tier['cash'] . ' cash has been added to your account.';
        } else {
            $resultMessage = 'This order was already completed. If you did not receive your cash, contact support.';
        }
    } catch (RuntimeException $e) {
        $resultMessage = 'Payment could not be completed. ' . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}
?>
<html>
<head><title>The Fallen Immortals - Purchase Complete</title></head>
<body style="background:#000;color:#fff;font-family:Arial,sans-serif;text-align:center;padding:40px;">
    <h2>Purchase Complete</h2>
    <p><?php echo $resultMessage; ?></p>
    <p><a href="<?php echo htmlspecialchars(db_base_url() . '/game2.php', ENT_QUOTES); ?>" style="color:#00DDDD;">Return to the game</a></p>
</body>
</html>