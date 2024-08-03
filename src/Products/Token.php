<?php

namespace LaravelTermii\Products;

use LaravelTermii\Traits\Termii;

class Token
{
    use Termii;

    /**
     * channel
     *
     * @var string
     */
    protected $channel = 'generic';

    /**
     * messageType
     *
     * @var string
     */
    protected $messageType = 'plain';

    /**
     * senderId
     *
     * @var string
     */
    protected $senderId = 'N-Alert';

    /**
     * deviceId
     *
     * @var string
     */
    protected $deviceId = 'N-Alert';

    /**
     * otp_message_type
     *
     * @var string
     */
    protected $otp_message_type = 'NUMERIC';

    /**
     * pin_type
     *
     * @var string
     */
    protected $pin_type = 'NUMERIC';

    /**
     * pin_attempts
     *
     * @var int
     */
    protected $pin_attempts = 10;

    /**
     * pin_time_to_live
     *
     * @var int
     */
    protected $pin_time_to_live = 5;

    /**
     * pin_length
     *
     * @var int
     */
    protected $pin_length = 6;

    /**
     * pin_placeholder
     *
     * @var string
     */
    protected $pin_placeholder = '<1234>';

    /**
     * request
     *
     * @var object
     */
    protected $request;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->getApiKey();
        $this->getBaseUrl();
        $this->senderId = $this->getSenderId();
        $this->deviceId = $this->getDeviceId();
        $this->setParams($params);

        $this->request = $this->request();
    }

    /**
     * Method setParams
     *
     *
     * @return void
     */
    public function setParams(array $params)
    {
        if (isset($params['message_type']) && $params['message_type']) {
            $this->otp_message_type = $params['message_type'];
        }

        if (isset($params['pin_type']) && $params['pin_type']) {
            $this->pin_type = $params['pin_type'];
        }

        if (isset($params['pin_attempts']) && $params['pin_attempts']) {
            $this->pin_attempts = $params['pin_attempts'];
        }

        if (isset($params['pin_time_to_live']) && $params['pin_time_to_live']) {
            $this->pin_time_to_live = $params['pin_time_to_live'];
        }

        if (isset($params['pin_length']) && $params['pin_length']) {
            $this->pin_length = $params['pin_length'];
        }

        if (isset($params['pin_placeholder']) && $params['pin_placeholder']) {
            $this->pin_placeholder = $params['pin_placeholder'];
        }

        if (isset($params['channel']) && $params['channel']) {
            $this->channel = $params['channel'];
        }
    }

    /**
     * Method setOptions
     */
    public function setOptions(array $params): object
    {
        $this->setParams($params);

        return $this;
    }

    /**
     * Method sendToken
     */
    public function sendToken(string $to, string $from, string $mesage, string $channel = 'generic'): object
    {

        $endpoint = $this->baseUrl.'/api/sms/otp/send';
        $payloads = $this->payload($this->apiKey);

        $payloads['to'] = $to; //email or phone
        $payloads['from'] = $from; // config id if email else sender id
        $payloads['channel'] = $channel;

        $payloads['pin_placeholder'] = $this->pin_placeholder;
        $payloads['message_type'] = $this->otp_message_type;
        $payloads['pin_attempts'] = (int) $this->pin_attempts;
        $payloads['pin_time_to_live'] = (int) $this->pin_time_to_live;
        $payloads['pin_length'] = (int) $this->pin_length;
        $payloads['pin_type'] = (int) $this->pin_type;
        $payloads['message_text'] = $mesage;

        return $this->request->post($endpoint, $payloads);
    }

    /**
     * Method voiceToken
     */
    public function voiceToken(string $phone_number): object
    {

        $endpoint = $this->baseUrl.'/api/sms/otp/send/voice';
        $payloads = $this->payload($this->apiKey);

        $payloads['phone_number'] = $phone_number;
        $payloads['pin_attempts'] = (int) $this->pin_attempts;
        $payloads['pin_time_to_live'] = (int) $this->pin_time_to_live;
        $payloads['pin_length'] = (int) $this->pin_length;

        return $this->request->post($endpoint, $payloads);
    }

    /**
     * Method voiceCall
     */
    public function voiceCall(string $phone_number, string $code): object
    {
        $payloads = $this->payload($this->apiKey);
        $endpoint = $this->baseUrl.'/api/sms/otp/call';
        $payloads['phone_number'] = $phone_number;
        $payloads['code'] = $code;

        return $this->request->post($endpoint, $payloads);
    }

    /**
     * Method emailToken
     */
    public function emailToken(string $email, string $code, $config_id): object
    {
        $endpoint = $this->baseUrl.'/api/email/otp/send';

        $payloads = $this->payload($this->apiKey);
        $payloads['email_address'] = $email;
        $payloads['code'] = $code;
        $payloads['email_configuration_id'] = $config_id;

        return $this->request->post($endpoint, $payloads);
    }

    /**
     * Method verifyToken
     */
    public function verifyToken(string $pin_id, string $pin): object
    {
        $endpoint = $this->baseUrl.'/api/sms/otp/verify';

        $payloads = $this->payload($this->apiKey);
        $payloads['pin_id'] = $pin_id;
        $payloads['pin'] = $pin;

        return $this->request->post($endpoint, $payloads);
    }

    /**
     * Method inAppToken
     */
    public function inAppToken(string $phone_number): object
    {

        $endpoint = $this->baseUrl.'/api/sms/otp/generate';
        $payloads = $this->payload($this->apiKey);

        $payloads['phone_number'] = $phone_number;
        $payloads['pin_attempts'] = (int) $this->pin_attempts;
        $payloads['pin_time_to_live'] = (int) $this->pin_time_to_live;
        $payloads['pin_length'] = (int) $this->pin_length;
        $payloads['pin_type'] = $this->pin_type;

        return $this->request->post($endpoint, $payloads);
    }
}
