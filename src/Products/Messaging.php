<?php

namespace LaravelTermii\Products;

use LaravelTermii\Traits\Termii;

class Messaging
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

    protected $senderId;

    /**
     * request
     *
     * @var mixed
     */
    protected $request;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct($senderId = null)
    {
        $this->getApiKey();
        $this->getBaseUrl();
        $this->senderId = $senderId ?? $this->getSenderId();
        $this->request = $this->request();
    }

    /**
     * Method fetchSenderID
     */
    public function fetchSenderID(): object
    {
        $endpoint = $this->baseUrl.'/api/sender-id/';

        return $this->request()->get($endpoint, $this->payload($this->apiKey));
    }

    /**
     * Method requestSenderID
     */
    public function requestSenderID(string $sender_id, string $usecase, string $company): object
    {
        $endpoint = $this->baseUrl.'/api/sender-id/request/';
        $payloads = $this->payload($this->apiKey);
        $payloads['sender_id'] = $sender_id;
        $payloads['usecase'] = $usecase;
        $payloads['company'] = $company;

        return $this->request->post($endpoint, $payloads);
    }

    /**
     * Method sendMessage
     */
    public function sendMessage(string $to, string $message, string $channel = 'generic'): object
    {
        $endpoint = $this->baseUrl.'/api/sms/send/';
        $payloads = $this->payload($this->apiKey);
        $payloads['to'] = $to;
        $payloads['from'] = $this->senderId;
        $payloads['sms'] = $message;
        $payloads['type'] = $this->messageType;
        $payloads['channel'] = $channel;

        return $this->request()->post($endpoint, $payloads);
    }

    /**
     * Method sendNumber
     */
    public function sendNumber(string $to, string $message): object
    {
        $endpoint = $this->baseUrl.'/api/sms/number/send/';
        $payloads = $this->payload($this->apiKey);
        $payloads['to'] = $to;
        $payloads['sms'] = $message;

        return $this->request()->post($endpoint, $payloads);
    }

    /**
     * Method sendBulkMessage
     *
     * @param  $channel  $channel
     */
    public function sendBulkMessage(array $to, string $message, $channel = 'generic'): object
    {
        $endpoint = $this->baseUrl.'/api/sms/send/bulk';
        $payloads = $this->payload($this->apiKey);
        $payloads['to'] = $to;
        $payloads['from'] = $this->senderId;
        $payloads['sms'] = $message;
        $payloads['type'] = $this->messageType;
        $payloads['channel'] = $channel;

        return $this->request()->post($endpoint, $payloads);
    }

    /**
     * Method sendWhatsapp
     */
    public function sendWhatsapp(string $to, string $deviceId, array $media): object
    {
        $channel = 'whatsapp';
        $endpoint = $this->baseUrl.'/api/sms/send/';
        $payloads = $this->payload($this->apiKey);
        $payloads['to'] = $to;
        $payloads['from'] = $deviceId;
        if ($deviceId) {
            $payloads['from'] = $deviceId;
        }
        $payloads['media'] = json_encode($media);
        $payloads['type'] = $this->messageType;
        $payloads['channel'] = $channel;

        return $this->request()->post($endpoint, $payloads);
    }

    /**
     * Method sendTemplate
     */
    public function sendTemplate(string $to, string $template_id, string $device_id, array $data): object
    {
        $endpoint = $this->baseUrl.'/api/send/template/';
        $payloads = $this->payload($this->apiKey);
        $payloads['phone_number'] = $to;
        $payloads['device_id'] = $device_id;
        $payloads['template_id'] = $template_id;
        $payloads['data'] = json_encode($data);

        return $this->request()->post($endpoint, $payloads);
    }

    /**
     * Method phonebooks
     */
    public function phonebooks(): object
    {
        $endpoint = $this->baseUrl.'/api/phonebooks/';

        return $this->request()->get($endpoint, $this->payload($this->apiKey));
    }

    /**
     * Method createPhoneBook
     */
    public function createPhoneBook(string $phonebook_name, string $desc = ''): object
    {
        $endpoint = $this->baseUrl.'/api/phonebooks/';
        $payloads = $this->payload($this->apiKey);
        $payloads['phonebook_name'] = $phonebook_name;
        $payloads['description'] = $desc;

        return $this->request()->post($endpoint, $payloads);
    }

    /**
     * Method updatePhoneBook
     */
    public function updatePhoneBook(string $phonebook_id, string $phonebook_name): object
    {
        $endpoint = $this->baseUrl."/api/phonebooks/{$phonebook_id}";
        $payloads = $this->payload($this->apiKey);
        $payloads['phonebook_name'] = $phonebook_name;

        return $this->request()->patch($endpoint, $payloads);
    }

    /**
     * Method deletePhoneBook
     */
    public function deletePhoneBook(string $phonebook_id): object
    {
        $endpoint = $this->baseUrl."/api/phonebooks/{$phonebook_id}";

        return $this->request()->delete($endpoint, $this->payload($this->apiKey));
    }

    /**
     * Method PhonebookContacts
     */
    public function PhonebookContacts(string $phonebook_id): object
    {
        $endpoint = $this->baseUrl."/api/phonebooks/{$phonebook_id}/contacts";

        return $this->request()->get($endpoint, $this->payload($this->apiKey));
    }

    /**
     * Method addContact
     */
    public function addContact(string $phonebook_id, array $contact): object
    {
        $endpoint = $this->baseUrl."/api/phonebooks/{$phonebook_id}/contacts";
        $payloads = array_merge($this->payload($this->apiKey), $contact);

        return $this->request()->post($endpoint, $payloads);
    }

    /**
     * Method addMutipleContacts
     */
    public function addMutipleContacts(string $phonebook_id, string $contact_file, string $country_code = ''): object
    {
        $endpoint = $this->baseUrl."/api/phonebooks/{$phonebook_id}/contacts";
        $payloads = $this->payload($this->apiKey);
        $payloads['contact_file'] = $contact_file;
        $payloads['country_code'] = $this->country_code;
        if ($country_code) {
            $payloads['country_code'] = $country_code;
        }

        return $this->request()->post($endpoint, $payloads);
    }

    /**
     * Method deleteContact
     */
    public function deleteContact(string $contact_id): object
    {
        $endpoint = $this->baseUrl."/api/phonebook/contact/{$contact_id}/";

        return $this->request()->delete($endpoint, $this->payload($this->apiKey));
    }

    /**
     * Method campaigns
     */
    public function campaigns(): object
    {
        $endpoint = $this->baseUrl.'/api/sms/campaigns/';

        return $this->request()->get($endpoint, $this->payload($this->apiKey));
    }

    /**
     * Method campaignHistory
     */
    public function campaignHistory(string $campaign_id): object
    {
        $endpoint = $this->baseUrl."/api/sms/campaigns/{$campaign_id}/";

        return $this->request()->get($endpoint, $this->payload($this->apiKey));
    }

    /**
     * Method sendCampaign
     */
    public function sendCampaign(string $phonebook_id, string $message, string $channel = 'generic', array $schedule = []): object
    {
        $endpoint = $this->baseUrl.'/api/sms/campaigns/send';
        $payloads = $this->payload($this->apiKey);
        //array_merge($this->payload($this->apiKey), $campaign);
        $payloads['phonebook_id'] = $phonebook_id;
        $payloads['message'] = $message;
        $payloads['sender_id'] = $this->senderId;
        $payloads['country_code'] = $this->country_code;
        $payloads['channel'] = $channel;
        $payloads['message_type'] = $this->messageType;
        $payloads['delimiter'] = ',';
        $payloads['remove_duplicate'] = 'yes';
        $payloads['campaign_type'] = 'campaign_type';

        if (isset($schedule['schedule_sms_status']) && $schedule['schedule_sms_status'] == 'scheduled') {
            $payloads['schedule_sms_status'] = $schedule['schedule_sms_status'];
            $payloads['schedule_time'] = $schedule['schedule_time'];
        }

        return $this->request()->post($endpoint, $payloads);
    }
}
