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
                $responseCode = $request->input('data.responseCode') ?? $request->input('responseCode');
                
                // Map codes to status and messages
                $status = 'failed';
                $gatewayMessage = 'Unknown Error';
                
                switch ($responseCode) {
                    case '0000':
                        $status = 'success';
                        $gatewayMessage = 'The transaction has been processed successfully';
                        break;
                    case '0001':
                        $status = 'pending';
                        $gatewayMessage = 'Request has been accepted. A callback will be sent on final state.';
                        break;
                    case '0005':
                        $gatewayMessage = 'There was an HTTP failure/exception when reaching the payment partner. The transaction state is not known.';
                        break;
                    case '2001':
                        $gatewayMessage = 'Transaction failed due to an error with the Payment Processor.';
                        break;
                    case '2100':
                        $gatewayMessage = "The request failed as the customer's phone is switched off.";
                        break;
                    case '2101':
                        $gatewayMessage = 'The transaction failed as the PIN entered by the Airtel Money customer is invalid.';
                        break;
                    case '2102':
                        $gatewayMessage = 'The Airtel Money user has insufficient funds in wallet to make this payment.';
                        break;
                    case '2103':
                        $gatewayMessage = 'The mobile number specified is not registered on Airtel Money';
                        break;
                    case '2050':
                        $gatewayMessage = 'The MTN Mobile Money user has insufficient funds in wallet to make this payment.';
                        break;
                    case '2051':
                        $gatewayMessage = 'The mobile number provided is not registered on MTN Mobile Money';
                        break;
                    case '2152':
                        $gatewayMessage = 'The mobile number specified is not registered on Tigo cash.';
                        break;
                    case '2153':
                        $gatewayMessage = 'The amount specified is more than the maximum allowed by Tigo Cash';
                        break;
                    case '2154':
                        $gatewayMessage = 'The amount specified is more than the maximum daily limit allowed by Tigo Cash.';
                        break;
                    case '4000':
                        $gatewayMessage = 'Validation Errors. Something is not quite right with this request.';
                        break;
                    case '4004':
                        $gatewayMessage = 'Client reference not found';
                        break;
                    default:
                        $gatewayMessage = 'Unknown response code: ' . ($responseCode ?? 'NULL');
                        break;
                }

                $responseBody = $request->all();
                $responseBody['gateway_message'] = $gatewayMessage;
                $responseBody['gateway_explanation'] = match($responseCode) {
                    '0005' => 'Please contact ISAAC.LARBI@MYUMBBANK.COM to confirm the status of this transaction',
                    '2001' => '1. Customer either entered no or invalid PIN 2. Mobile network not able to parse your request. 3. USSD session timeout. 4. Having strange characters (&*!%@) in your description.',
                    '2050' => 'Customer has to top up mobile money wallet with funds more than the amount being charged.',
                    '2051' => 'Ensure that the mobile number is registered on the MTN channel.',
                    default => null
                };

                $transaction->update([
                    'status' => $status,
                    'response_code' => $responseCode,
                    'response_body' => $responseBody,
                    // We do NOT update 'message' here to preserve the customer's "Wish for the couple"
                    'transaction_id' => $request->input('data.transactionId') ?? $request->input('transactionId') ?? $transaction->transaction_id,
                ]);
            }
        }

        return response()->json(['message' => 'Callback processed']);
    }
}
