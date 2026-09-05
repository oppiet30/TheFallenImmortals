<?php
// Creates a PayPal Orders v2 order for the requested cash tier and returns the
// PayPal approval URL so the client can redirect the buyer there.

session_name("fallenimmortals");
session_start();
include('db.php');

$getchar = db_query("SELECT * FROM characters WHERE id=?", [$_SESSION['userid']]);
$char = db_fetch_assoc($getchar);

if ($char === false || ($char['username'] ?? '') === '') {
    die("You need to login to view this content.");
}

ob_get_clean();
header('Content-Type: application/json');

// Keep tier definitions in sync with completeIPN.php and purchase.php.
$tiers = [
    'FIVE_CASH'       => ['price' => '5.25',  'cash' => 5,   'title' => '5 Cash'],
    'TEN_CASH'        => ['price' => '10.50', 'cash' => 11,  'title' => '11 Cash'],
    'TWENTY_CASH'     => ['price' => '21.00', 'cash' => 23,  'title' => '23 Cash'],
    'FIFTY_CASH'      => ['price' => '52.50', 'cash' => 58,  'title' => '58 Cash'],
    'ONEHUNDRED_CASH' => ['price' => '105.00', 'cash' => 120, 'title' => '120 Cash'],
];

$tierKey = (string)($_POST['tier'] ?? '');
$tier = $tiers[$tierKey] ?? null;
if ($tier === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Unknown tier.']);
    exit;
}

require_once __DIR__ . '/src/PayPal.php';

$returnUrl = db_base_url() . '/capturePaypalOrder.php';
$cancelUrl = db_base_url() . '/purchase.php';

try {
    $paypal = new PayPal();
    $order = $paypal->createOrder($tier['price'], $tier['title'], $tierKey, $returnUrl, $cancelUrl);
    $approvalLink = $paypal->links['approve'] ?? '';

    if ($approvalLink === '') {
        throw new RuntimeException('PayPal returned no approval link.');
    }

    echo json_encode([
        'orderId' => $order['id'],
        'approveUrl' => $approvalLink,
    ]);
} catch (RuntimeException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}