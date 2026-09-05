<?php
// PayPal webhook listener - credits game cash when a payment is captured.
//
// Register this URL in the PayPal Developer Dashboard under your App's
// Webhooks section (must be HTTPS). Select the PAYMENT.CAPTURE.COMPLETED
// event. The Webhook ID from that section belongs in paypal-config.php.

require_once __DIR__ . '/db-conn.php';
require_once __DIR__ . '/src/PayPal.php';

$body = file_get_contents('php://input');

if ($body === false || $body === '') {
    http_response_code(400);
    exit('empty body');
}

$headers = array_change_key_case(getallheaders(), CASE_UPPER);

$transmissionId = $headers['PAYPAL-TRANSMISSION-ID'] ?? '';
$transmissionTime = $headers['PAYPAL-TRANSMISSION-TIME'] ?? '';
$signature = $headers['PAYPAL-TRANSMISSION-SIG'] ?? '';
$certUrl = $headers['PAYPAL-CERT-URL'] ?? '';
$authAlgo = $headers['PAYPAL-AUTH-ALGO'] ?? '';

if ($transmissionId === '' || $signature === '' || $certUrl === '' || $authAlgo === '') {
    http_response_code(400);
    exit('missing webhook headers');
}

try {
    $paypal = new PayPal();
} catch (RuntimeException $e) {
    http_response_code(500);
    exit('PayPal not configured');
}

// Signature verification before trusting anything.
if (!$paypal->verifyWebhook($body, $transmissionId, $transmissionTime, $signature, $certUrl, $authAlgo)) {
    http_response_code(400);
    exit('invalid signature');
}

$event = json_decode($body, true);
if (!is_array($event)) {
    http_response_code(400);
    exit('invalid json');
}

// Only credit cash on completed captures. PayPal may retry redeliveries of the
// same event (idempotency handled in creditCashByTxn).
if (($event['event_type'] ?? '') !== 'PAYMENT.CAPTURE.COMPLETED') {
    http_response_code(200);
    exit('ignored');
}

$resource = $event['resource'] ?? [];
$txnId = (string)($resource['id'] ?? '');
$amount = $resource['amount']['value'] ?? null;
$currency = $resource['amount']['currency_code'] ?? 'USD';
$customId = (string)($resource['custom_id'] ?? '');

if ($txnId === '' || $amount === null || $currency !== 'USD') {
    http_response_code(400);
    exit('missing txn fields');
}

// Map the custom_id price tier to the game cash amount (same rates as the
// purchase page). Sources of truth: the tier constants below must stay in sync
// with createPaypalOrder.php and purchase.php.
$tiers = [
    'FIVE_CASH'       => ['price' => '5.25',  'cash' => 5,   'title' => '5 Cash'],
    'TEN_CASH'        => ['price' => '10.50', 'cash' => 11,  'title' => '11 Cash'],
    'TWENTY_CASH'     => ['price' => '21.00', 'cash' => 23,  'title' => '23 Cash'],
    'FIFTY_CASH'      => ['price' => '52.50', 'cash' => 58,  'title' => '58 Cash'],
    'ONEHUNDRED_CASH' => ['price' => '105.00', 'cash' => 120, 'title' => '120 Cash'],
];

$tier = $tiers[$customId] ?? null;
if ($tier === null) {
    http_response_code(400);
    exit('unknown tier');
}
if (rtrim((string)$amount, '0.') !== rtrim($tier['price'], '0.')) {
    http_response_code(400);
    exit('amount mismatch');
}

$date = time();

function creditCashByTxn(string $txnId, int $cash, int $networth, string $payerEmail, string $username, int $date): void
{
    $check = db_query('SELECT id FROM log WHERE message LIKE ?', ['%' . $txnId . '%']);
    if (db_num_rows($check) > 0) {
        return; // already processed this transaction (idempotent)
    }

    db_query(
        'UPDATE characters SET networth = networth + ?, cash = cash + ? WHERE username = ?',
        [$networth, $cash, $username]
    );

    $message = "<b><font color=\\'#9933FF\\'>" . $username . ' just made a purchase for ' . $cash
        . ' game cash!<br />Congratulations on all your success!</font></b><br />';
    db_query(
        "INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')",
        [$date, $username, $message]
    );

    $logNote = 'PayPal txn ' . $txnId . ' (' . $payerEmail . ')';
    db_query(
        'INSERT INTO `log` (`name`, `message`) VALUES (?, ?)',
        [$username, $logNote]
    );
}

$payerEmail = (string)($event['resource']['payer']['email_address'] ?? '');

$getchar = db_query('SELECT username FROM characters WHERE email = ?', [$payerEmail]);
$char = db_fetch_assoc($getchar);

if ($char === false || ($char['username'] ?? '') === '') {
    // No account with that email. Still acknowledge privately, no credit.
    http_response_code(200);
    exit('no matching account');
}

creditCashByTxn(
    $txnId,
    (int)$tier['cash'],
    (int)$tier['cash'],
    $payerEmail,
    $char['username'],
    $date
);

http_response_code(200);
exit('ok');