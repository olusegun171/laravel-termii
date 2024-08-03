<?php

namespace LaravelTermii\Traits;

use Exception;
use Illuminate\Support\Facades\Http;

trait Termii
{
    /**
     * country_code
     *
     * @var string
     */
    public $country_code = '234';

    protected string $baseUrl;

    protected string $apiKey;

    /**
     * Method getApiKey
     */
    public function getApiKey(): void
    {
        $apiKey = env('TERMII_API_KEY');
        if (empty($apiKey)) {
            throw new Exception('Termii API Key is required.');
        }
        $this->apiKey = $apiKey;
    }

    /**
     * Method getSenderId
     */
    public function getSenderId(): string
    {
        return env('TERMII_SENDER_ID', 'N-Alert');
    }

    /**
     * Method getDeviceId
     */
    public function getDeviceId(): string
    {
        return env('TERMII_DEVICE_ID', 'talert');
    }

    /**
     * Method getBaseUrl
     */
    public function getBaseUrl(): void
    {
        $baseUrl = env('TERMII_BASE_URL');
        if (empty($baseUrl)) {
            throw new Exception('Termii base url is not defined.');
        }
        $this->baseUrl = rtrim($baseUrl, '/\\');
    }

    /**
     * Method request
     */
    public function request(): object
    {
        return Http::withHeaders(['Content-Type' => 'application/json']);
    }

    /**
     * Method payload
     *
     * @param  string  $apiKey  [Termii Apikey]
     */
    protected function payload(string $apiKey): array
    {
        return ['api_key' => $apiKey];
    }
}
