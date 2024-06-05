<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VTPassService
{
    protected $baseUrl;
    protected $username;
    protected $password;

    public function __construct()
    {
        $this->baseUrl = config('services.vtpass.base_url');
        $this->username = config('services.vtpass.username');
        $this->password = config('services.vtpass.password');
    }

    public function makeRequest($endpoint, $data)
    {
        $response = Http::withBasicAuth($this->username, $this->password)
            ->post("{$this->baseUrl}/$endpoint", $data);

        return $response->json();
    }

    public function buyAirtime($network, $phone, $amount)
    {
        return $this->makeRequest('pay', [
            'serviceID' => $network,
            'amount' => $amount,
            'phone' => $phone,
        ]);
    }

    public function payElectricityBill($serviceID, $meterNumber, $amount, $phone)
    {
        return $this->makeRequest('pay', [
            'serviceID' => $serviceID,
            'meter_number' => $meterNumber,
            'amount' => $amount,
            'phone' => $phone,
        ]);
    }

    public function buyData($network, $phone, $amount)
    {
        return $this->makeRequest('pay', [
            'serviceID' => $network,
            'amount' => $amount,
            'phone' => $phone,
        ]);
    }

    public function subscribeTV($serviceID, $smartCardNumber, $amount, $phone)
    {
        return $this->makeRequest('pay', [
            'serviceID' => $serviceID,
            'smartcard_number' => $smartCardNumber,
            'amount' => $amount,
            'phone' => $phone,
        ]);
    }
}
