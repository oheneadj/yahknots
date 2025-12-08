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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function handleCallback(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Payment Callback Received', $request->all());

        // The payload usually comes in 'data' object or directly in root depending on the provider wrapper
        $clientReference = $request->input('data.clientReference') ?? $request->input('clientReference');

        if ($clientReference) {
            $transaction = \App\Models\Transaction::where('client_reference', $clientReference)->first();

            if ($transaction) {
                // According to doc: 0000 = Success, 0001 = Pending (should technically be final status in callback but handling just in case)
                $responseCode = $request->input('responseCode');
                $status = 'failed';
                $message = $request->input('message') ?? 'Transaction processed';

                if ($responseCode === '0000') {
                    $status = 'success';
                    $message = 'Transaction successful';
                } elseif ($responseCode === '0001') {
                    $status = 'pending'; // Still pending?
                }
                
                // Map common failure codes for better logging
                $failureMap = [
                    '0005' => 'HTTP failure/exception at payment partner. Status unknown.',
                    '2001' => 'Payment Processor Error: Invalid PIN, timeout, or format error.',
                    '2100' => 'Customer phone is switched off.',
                    
                    // Airtel
                    '2101' => 'Invalid PIN (Airtel).',
                    '2102' => 'Insufficient funds (Airtel).',
                    '2103' => 'Number not registered on Airtel Money.',
                    
                    // MTN
                    '2050' => 'Insufficient funds (MTN).',
                    '2051' => 'Number not registered on MTN Mobile Money.',
                    
                    // Tigo
                    '2152' => 'Number not registered on Tigo Cash.',
                    '2153' => 'Amount exceeds maximum allowed (Tigo).',
                    '2154' => 'Amount exceeds daily limit (Tigo).',
                    
                    // General
                    '4000' => 'Validation Error. Check request details.',
                    '4004' => 'Client reference not found.',
                ];

                if (isset($failureMap[$responseCode])) {
                    $message = $failureMap[$responseCode];
                }

                $transaction->update([
                    'status' => $status,
                    'response_code' => $responseCode,
                    'response_body' => $request->all(),
                    'message' => $status === 'failed' ? $message : $transaction->message, // Update message on failure
                    'transaction_id' => $request->input('data.transactionId') ?? $request->input('transactionId') ?? $transaction->transaction_id,
                ]);
            }
        }

        return response()->json(['message' => 'Callback processed']);
    }
}
