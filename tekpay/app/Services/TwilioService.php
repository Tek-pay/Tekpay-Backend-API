<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(config('services.twilio.sid'), config('services.twilio.token'));
    }

    public function sendOTP($phoneNumber, $otp)
    {
        $message = "Your OTP for verification: $otp";

        try {
            $this->client->messages->create(
                $phoneNumber,
                [
                    'from' => config('services.twilio.phone_number'),
                    'body' => $message
                ]
            );
            return true;
        } catch (\Exception $e) {
            // Log or handle the exception
            return false;
        }
    }
}
