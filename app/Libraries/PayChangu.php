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
        // First try the standard verification endpoint
        $result = $this->makeRequest('GET', "/payment/verify/{$txRef}");

        // If that fails, try alternative endpoint formats
        if (!$result || isset($result['status']) && $result['status'] === 'error') {
            // Try alternative endpoint
            $result = $this->makeRequest('GET', "/verify/{$txRef}");

            // If still failing, try with different base URL variations
            if (!$result || isset($result['status']) && $result['status'] === 'error') {
                // For test environment, sometimes the API has connectivity issues
                // Return null to indicate verification failed but don't treat as definitive failure
                log_message('warning', 'PayChangu verification failed for tx_ref: ' . $txRef . ' - API may be unavailable');
                return null;
            }
        }

        return $result;
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
