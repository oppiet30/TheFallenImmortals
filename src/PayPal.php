<?php

declare(strict_types=1);

/**
 * Minimal PayPal REST (Orders v2 + Webhooks) client built on cURL.
 *
 * No external SDK dependency: this project is plain procedural PHP, so we talk
 * to the PayPal REST API directly. Safe to use behind the OOP DB layer and the
 * db_* helpers.
 *
 * Usage:
 *   $pp = new PayPal();
 *   $order = $pp->createOrder(5.25, '5 Cash', 'FIVE_CASH');
 *   $pp->approvalLink = $order['links']['approve'];
 *   ...
 *   $capture = $pp->captureOrder($orderId);
 */
final class PayPal
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;
    private string $webhookId;
    private ?string $accessToken = null;

    /** @var array<string,string> links extracted from the last order */
    public array $links = [];

    public function __construct(?array $config = null)
    {
        $config ??= include __DIR__ . '/../paypal-config.php';

        $mode = ($config['mode'] ?? 'live') === 'sandbox' ? 'sandbox' : 'live';
        $key = "$mode" . '_';

        $this->baseUrl = $mode === 'sandbox'
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';
        $this->clientId = (string)($config[$key . 'client_id'] ?? '');
        $this->clientSecret = (string)($config[$key . 'client_secret'] ?? '');
        $this->webhookId = (string)($config[$key . 'webhook_id'] ?? '');

        if ($this->clientId === '' || $this->clientSecret === '') {
            throw new RuntimeException('PayPal credentials are not configured.');
        }
    }

    /** Fetch and cache a short-lived OAuth access token. */
    public function getAccessToken(): string
    {
        if ($this->accessToken !== null) {
            return $this->accessToken;
        }

        $auth = base64_encode($this->clientId . ':' . $this->clientSecret);
        $resp = $this->request(
            'POST',
            '/v1/oauth2/token',
            [],
            [
                CURLOPT_HTTPHEADER => [
                    'Authorization: Basic ' . $auth,
                    'Accept: application/json',
                ],
                CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            ]
        );

        if (!isset($resp['access_token'])) {
            throw new RuntimeException('PayPal failed to issue an access token.');
        }

        return $this->accessToken = $resp['access_token'];
    }

    private function authHeader(): array
    {
        return ['Authorization: Bearer ' . $this->getAccessToken()];
    }

    /**
     * Create an Orders v2 (CAPTURE intent) order.
     *
     * @param string $value two-decimal amount string e.g. "5.25"
     * @param string $description human-readable item name
     * @param string $customId stable id used to map back to a tier (e.g. FIVE_CASH)
     * @param string $returnUrl where the buyer returns after approving
     * @param string $cancelUrl where the buyer returns after cancelling
     * @return array{id:string,purchase_units:array,links:array}
     */
    public function createOrder(
        string $value,
        string $description,
        string $customId,
        string $returnUrl,
        string $cancelUrl
    ): array {
        $body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => $customId,
                'custom_id' => $customId,
                'description' => $description,
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => $value,
                ],
            ]],
            'application_context' => [
                'brand_name' => 'The Fallen Immortals',
                'user_action' => 'PAY_NOW',
                'shipping_preference' => 'NO_SHIPPING',
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
            ],
        ];

        $resp = $this->request('POST', '/v2/checkout/orders', $body, [
            CURLOPT_HTTPHEADER => array_merge($this->authHeader(), [
                'Content-Type: application/json',
                'Prefer: return=representation',
            ]),
        ]);

        if (!isset($resp['id'])) {
            throw new RuntimeException('PayPal order could not be created.');
        }

        foreach (($resp['links'] ?? []) as $link) {
            if (is_array($link)) {
                $this->links[$link['rel'] ?? ''] = $link['href'] ?? '';
            }
        }

        return $resp;
    }

    /**
     * Capture an approved order. Returns the capture response, or null if the
     * order is already fully captured (idempotent retry).
     */
    public function captureOrder(string $orderId): ?array
    {
        $resp = $this->request(
            'POST',
            "/v2/checkout/orders/$orderId/capture",
            [],
            [
                CURLOPT_HTTPHEADER => array_merge($this->authHeader(), [
                    'Content-Type: application/json',
                    'Prefer: return=representation',
                ]),
            ]
        );

        if (($resp['status'] ?? '') === 'COMPLETED') {
            return $resp;
        }

        // 422 UNPROCESSABLE_ENTITY with COMPLETED status => already captured.
        if (
            isset($resp['details'][0]['issue'])
            && stripos((string)$resp['details'][0]['issue'], 'ORDER_ALREADY_CAPTURED') !== false
        ) {
            return null;
        }

        throw new RuntimeException('PayPal capture failed: ' . json_encode($resp));
    }

    /**
     * Verify a webhook event signature using the PayPal Verify-Webhook-Signature
     * API. Returns true when the event is authentic.
     */
    public function verifyWebhook(
        string $body,
        string $transmissionId,
        string $transmissionTime,
        string $signature,
        string $certUrl,
        string $authAlgo
    ): bool {
        $payload = [
            'auth_algo' => $authAlgo,
            'cert_url' => $certUrl,
            'transmission_id' => $transmissionId,
            'transmission_sig' => $signature,
            'transmission_time' => $transmissionTime,
            'webhook_id' => $this->webhookId,
            'webhook_event' => json_decode($body, true) ?? [],
        ];

        $resp = $this->request(
            'POST',
            '/v1/notifications/verify-webhook-signature',
            $payload,
            [CURLOPT_HTTPHEADER => array_merge($this->authHeader(), ['Content-Type: application/json'])]
        );

        return ($resp['verification_status'] ?? '') === 'SUCCESS';
    }

    /**
     * Perform a JSON cURL request to the PayPal API.
     */
    private function request(string $method, string $path, array $jsonBody = [], array $extraOpts = []): array
    {
        $ch = curl_init($this->baseUrl . $path);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ] + $extraOpts);

        $raw = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $httpStatus = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($raw === false) {
            throw new RuntimeException("PayPal cURL error ($errno): $error");
        }

        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            $decoded['_http_status'] = $httpStatus;
            return $decoded;
        }

        // Some responses (token) have no useful JSON body shape; keep raw.
        return ['_http_status' => $httpStatus, '_raw' => $raw];
    }
}
