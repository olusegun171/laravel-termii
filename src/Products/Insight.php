<?php

namespace LaravelTermii\Products;

use LaravelTermii\Traits\Termii;

class Insight
{
    use Termii;

    protected object $request;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->getApiKey();
        $this->getBaseUrl();
        $this->request = $this->request();
    }

    /**
     * Method balance
     */
    public function balance(): object
    {
        $endpoint = $this->baseUrl.'/api/get-balance/';
        $payloads = $this->payload($this->apiKey);

        return $this->request->get($endpoint, $payloads);
    }

    /**
     * Method search
     *
     * @param  string  $phone_number  [customer phone number]
     */
    public function search(string $phone_number): object
    {
        $endpoint = $this->baseUrl.'/api/check/dnd/';
        $payloads = $this->payload($this->apiKey);
        $payloads['phone_number'] = $phone_number;

        return $this->request->get($endpoint, $payloads);
    }

    /**
     * Method status
     */
    public function status(string $phone_number, string $country_code = 'NG'): object
    {
        $endpoint = $this->baseUrl.'/api/insight/number/query/';
        $payloads = $this->payload($this->apiKey);
        $payloads['phone_number'] = $phone_number;
        $payloads['country_code'] = $country_code;

        return $this->request->get($endpoint, $payloads);
    }

    /**
     * Method history
     */
    public function history(): object
    {
        $endpoint = $this->baseUrl.'/api/sms/inbox/';
        $payloads = $this->payload($this->apiKey);

        return $this->request->get($endpoint, $payloads);
    }
}
