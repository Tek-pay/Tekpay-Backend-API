<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VTPassService;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class BillPaymentController extends Controller
{
    protected $vtpassService;

    public function __construct(VTPassService $vtpassService)
    {
        $this->vtpassService = $vtpassService;
    }

    public function buyAirtime(Request $request)
    {
        $request->validate([
            'network' => 'required|string',
            'phone' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $response = $this->vtpassService->buyAirtime(
            $request->network,
            $request->phone,
            $request->amount
        );

        $this->logTransaction($request, $response, 'airtime');

        return response()->json($response);
    }

    public function payElectricityBill(Request $request)
    {
        $request->validate([
            'serviceID' => 'required|string',
            'meter_number' => 'required|string',
            'amount' => 'required|numeric',
            'phone' => 'required|string',
        ]);

        $response = $this->vtpassService->payElectricityBill(
            $request->serviceID,
            $request->meter_number,
            $request->amount,
            $request->phone
        );

        $this->logTransaction($request, $response, 'electricity');

        return response()->json($response);
    }

    public function buyData(Request $request)
    {
        $request->validate([
            'network' => 'required|string',
            'phone' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $response = $this->vtpassService->buyData(
            $request->network,
            $request->phone,
            $request->amount
        );

        $this->logTransaction($request, $response, 'data');

        return response()->json($response);
    }

    public function subscribeTV(Request $request)
    {
        $request->validate([
            'serviceID' => 'required|string',
            'smartcard_number' => 'required|string',
            'amount' => 'required|numeric',
            'phone' => 'required|string',
        ]);

        $response = $this->vtpassService->subscribeTV(
            $request->serviceID,
            $request->smartcard_number,
            $request->amount,
            $request->phone
        );

        $this->logTransaction($request, $response, 'tv');

        return response()->json($response);
    }

    private function logTransaction($request, $response, $type)
    {
        Transaction::create([
            'user_id' => Auth::id(),
            'type' => $type,
            'service_id' => $request->serviceID ?? $request->network,
            'amount' => $request->amount,
            'status' => $response['response_code'] === '000' ? 'success' : 'failed',
            'transaction_id' => $response['requestId'],
            'response' => json_encode($response),
        ]);
    }
}
