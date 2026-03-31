<?php

namespace App\Libraries;

use CodeIgniter\HTTP\CURLRequest;

class PayChangu
{
    private $baseUrl;
    private $publicKey;
    private $secretKey;

    public function __construct()
    {
        $this->publicKey = getenv('PAYCHANGU_PUBLIC_KEY') ?: 'PUB-TEST-MB33j3iotOje4NXksN3UxQh8D9vZDYTk';
        $this->secretKey = getenv('PAYCHANGU_SECRET_KEY') ?: 'SEC-TEST-boVHQZcRxP7rZRT84OxEdQ403nCx2R3J';

        // Use test/sandbox API for test keys, live API for production keys
        $isTestKey = stripos($this->publicKey, 'PUB-TEST-') === 0 || stripos($this->secretKey, 'SEC-TEST-') === 0;
        $this->baseUrl = $isTestKey ? 'https://api-test.paychangu.com' : 'https://api.paychangu.com';
    }

    /**
     * Initialize a payment
     */
    public function initializePayment($data)
    {
        $payload = [
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'MWK',
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'callback_url' => $data['callback_url'],
            'return_url' => $data['return_url'],
            'tx_ref' => $data['tx_ref'],
            'customization' => [
                'title' => $data['title'] ?? 'TutorConnect Malawi Subscription',
                'description' => $data['description'] ?? 'Subscription payment for educational services'
            ]
        ];

        return $this->makeRequest('POST', '/payment/initialize', $payload);
    }

    /**
     * Verify payment status
     * Try multiple endpoints for better reliability
     */
    public function verifyPayment($txRef)
    {
        $txRef = trim((string) $txRef);
        if ($txRef === '') {
            return [
                'status' => 'error',
                'message' => 'Missing transaction reference.',
            ];
        }

        $endpoints = [
            "/verify-payment/{$txRef}",
            "/payment/verify/{$txRef}",
            "/verify/{$txRef}",
        ];

        foreach ($endpoints as $endpoint) {
            $result = $this->makeRequest('GET', $endpoint);

            if (is_array($result) && ($result['status'] ?? '') === 'success') {
                return $result;
            }
        }

        // For test environments, DNS or API availability can be flaky. Returning null
        // allows the caller to decide whether a non-destructive fallback is appropriate.
        log_message('warning', 'PayChangu verification failed for tx_ref: ' . $txRef . ' - API may be unavailable');
        return null;
    }

    /**
     * Confirm that the verification response represents a completed payment.
     */
    public function isSuccessfulVerification(?array $result, string $expectedTxRef = '', string $expectedCurrency = '', ?float $expectedAmount = null): bool
    {
        if (!is_array($result) || strtolower(trim((string) ($result['status'] ?? ''))) !== 'success') {
            return false;
        }

        $data = $result['data'] ?? null;
        if (!is_array($data)) {
            return false;
        }

        $paymentStatus = strtolower(trim((string) ($data['status'] ?? '')));
        if (!in_array($paymentStatus, ['success', 'successful', 'completed', 'paid'], true)) {
            return false;
        }

        if ($expectedTxRef !== '' && trim((string) ($data['tx_ref'] ?? '')) !== $expectedTxRef) {
            return false;
        }

        if ($expectedCurrency !== '' && strtoupper(trim((string) ($data['currency'] ?? ''))) !== strtoupper($expectedCurrency)) {
            return false;
        }

        if ($expectedAmount !== null && abs((float) ($data['amount'] ?? 0) - $expectedAmount) > 0.01) {
            return false;
        }

        return true;
    }

    /**
     * Make HTTP request to PayChangu API
     */
    private function makeRequest($method, $endpoint, $data = null)
    {
        $client = \Config\Services::curlrequest([
            'baseURI' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);

        $options = [];

        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $options['json'] = $data;
        }

        try {
            $response = $client->request($method, $endpoint, $options);

            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }

            return [
                'status' => 'error',
                'message' => 'API request failed',
                'status_code' => $response->getStatusCode(),
                'response' => $response->getBody()
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get public key for frontend
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }
}
