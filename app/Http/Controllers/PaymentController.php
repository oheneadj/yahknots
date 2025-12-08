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

        $clientReference = $request->input('clientReference') ?? $request->input('data.clientReference');

        if ($clientReference) {
            $transaction = \App\Models\Transaction::where('client_reference', $clientReference)->first();

            if ($transaction) {
                $status = $request->input('responseCode') === '0001' ? 'success' : 'failed';
                
                $transaction->update([
                    'status' => $status,
                    'response_code' => $request->input('responseCode'),
                    'response_body' => $request->all(),
                ]);
            }
        }

        return response()->json(['message' => 'Callback processed']);
    }
}
