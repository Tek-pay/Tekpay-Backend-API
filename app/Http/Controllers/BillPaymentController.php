<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VTPassService;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
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
            'phone' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        $requestId = $this->generateRequestId();
        $response = $this->vtpassService->buyAirtime(
            $requestId,
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
            'type' => 'required|string',
            'phone' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $requestId = $this->generateRequestId();
        $response = $this->vtpassService->payElectricityBill(
            $requestId,
            $request->serviceID,
            $request->meter_number,
            $request->type,
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

        $requestId = $this->generateRequestId();
        $response = $this->vtpassService->buyData(
            $requestId,
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

        $requestId = $this->generateRequestId();
        $response = $this->vtpassService->subscribeTV(
            $requestId,
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
        if ($response['code']) {
            Transaction::create([
                'user_id' => Auth::id(),
                'type' => $type,
                'service_id' => $request->serviceID ?? $request->network,
                'amount' => $request->amount,
                'status' => $response['code'] === '000' ? 'success' : 'failed',
                'transaction_id' => $response['requestId'] ?? $this->generateRequestId(),
                'response' => json_encode($response),
            ]);
        } else {
            throw new \Exception('Request failed');
        }
    }

    private function generateRequestId()
    {
        // Get the current date and time in Africa/Lagos timezone
        $date = Carbon::now('Africa/Lagos')->format('YmdHi'); // Format: YYYYMMDDHHII

        // Generate a random alphanumeric string
        $randomString = bin2hex(random_bytes(8)); // Generates a 16-character string

        // Concatenate the date and random string
        $requestId = $date . $randomString;

        // Ensure the final string is at least 12 characters (it will be, as the date part is 12 characters)
        if (strlen($requestId) < 12) {
            throw new \Exception('Request ID is less than 12 characters, which should not happen.');
        }

        return $requestId;
    }
}
