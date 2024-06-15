<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

use function PHPUnit\Framework\throwException;

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
        $url = $this->baseUrl  . $endpoint;
        $response = Http::withBasicAuth($this->username, $this->password)
            ->post($url, $data);
        return $response->json();
    }

    public function buyAirtime($requestId, $network, $phone, $amount)
    {

        return $this->makeRequest('pay', [
            'request_id' => $requestId,
            'serviceID' => $network,
            'amount' => $amount,
            'phone' => $phone,
        ]);
    }

    public function verifyElectricityBill($serviceID, $meterNumber,$type)
    {
        $data =
        [
            'serviceID' => $serviceID,
            'billersCode' => $meterNumber,
            'type' => $type
        ];
        $url = $this->baseUrl  . 'merchant-verify';
        $response = Http::withBasicAuth($this->username, $this->password)
            ->post($url, $data);

             if(isset($response['content']['error'])){
                 return response()->json([
                    'status' => 'error',
                    'message'=>'Merchant verify failed: ' . $response['content']['error'],
                ],402);

             }else{
                return [
                    'status' => 'success',
                    'message'=>'Merchant verify success',
                    'data' => json_encode($response->body()),
                ];
             };
    }

    public function payElectricityBill($requestId, $serviceID, $billersCode, $variation_code,$amount,$phone)
    {



        return $this->makeRequest('pay', [
            'request_id' => $requestId,
            'serviceID' => $serviceID,
            'billersCode' => (string) $billersCode,
            'variation_code' => $variation_code,
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
