<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('payment');
    }


    public function handleCallback(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Payment Callback Received', $request->all());

        // The payload usually comes in 'data' object or directly in root depending on the provider wrapper
        $clientReference = $request->input('data.clientReference') ?? $request->input('clientReference');

        if ($clientReference) {
            $transaction = \App\Models\Transaction::where('client_reference', $clientReference)->first();

            if ($transaction) {
                $responseCode = $request->input('responseCode');
                $status = 'failed';
                
                // 0000 is the specific success code per documentation
                if ($responseCode === '0000') {
                    $status = 'success';
                } elseif ($responseCode === '0001') {
                    $status = 'pending';
                }

                $transaction->update([
                    'status' => $status,
                    'response_code' => $responseCode,
                    'response_body' => $request->all(),
                    // We do NOT update 'message' here to preserve the customer's "Wish for the couple"
                    'transaction_id' => $request->input('data.transactionId') ?? $request->input('transactionId') ?? $transaction->transaction_id,
                ]);
            }
        }

        return response()->json(['message' => 'Callback processed']);
    }
}
