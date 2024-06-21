<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\Transaction;

class WalletController extends Controller
{
    public function balance()
    {
        $wallet = Auth::user()->wallet;
        return response()->json(['balance' => $wallet->balance]);
    }

    public function transactions()
    {
        $transactions = Auth::user()->transactions;
        return response()->json($transactions);
    }

    // Method to add balance 
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $wallet = Auth::user()->wallet;
        $wallet->balance += $request->amount;
        $wallet->save();

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'type' => 'deposit',
            'amount' => $request->amount,
            'status' => 'completed',
            'reference' => uniqid(),
        ]);

        return response()->json(['message' => 'Deposit successful', 'transaction' => $transaction]);
    }
}
