<?php
// PayPal REST API credentials. Copy this file to paypal-config.php and fill in.
//
// To create credentials: https://developer.paypal.com -> Apps & Credentials ->
// Create App. Grab the Client ID and Secret (Live or Sandbox). For webhook
// verification you also need the Webhook ID under the app's Webhooks section.

return [
    // 'live' or 'sandbox'
    'mode' => 'live',

    // Live credentials (api-m.paypal.com)
    'live_client_id'     => 'YOUR_LIVE_CLIENT_ID',
    'live_client_secret' => 'YOUR_LIVE_CLIENT_SECRET',
    'live_webhook_id'    => 'YOUR_LIVE_WEBHOOK_ID',

    // Sandbox credentials (api-m.sandbox.paypal.com)
    'sandbox_client_id'     => 'YOUR_SANDBOX_CLIENT_ID',
    'sandbox_client_secret' => 'YOUR_SANDBOX_CLIENT_SECRET',
    'sandbox_webhook_id'    => 'YOUR_SANDBOX_WEBHOOK_ID',
];