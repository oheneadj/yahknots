<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;

class PaymentForm extends Component
{
    public $name;
    public $number;
    public $network;
    public $amount;
    public $message;
    public $clientReference;
    public $response = [];
    public $paymentStatus = 'pending'; // pending, success, error
    public $errorMessage = '';
    public $detailedError = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'number' => 'required|string|min:10|max:15',
        'network' => 'required|string|in:mtn-gh,vodafone-gh,tigo-gh',
        'amount' => 'required|numeric|min:0.1|regex:/^\d+(\.\d{1,2})?$/',
        'message' => 'nullable|string|max:500',
    ];

    /**
     * Processes the payment by sending a request to the UMB Bank API.
     */
    public function processPayment()
    {
        $this->validate();
        $this->paymentStatus = 'processing';
        $this->errorMessage = '';
        $this->detailedError = '';

        $description = "eGift-Table";

        $this->clientReference = uniqid('UMB_', true);

        try {
            // Create pending transaction
            $transaction = Transaction::create([
                'client_reference' => $this->clientReference,
                'customer_name' => $this->name,
                'customer_number' => $this->number,
                'network' => $this->network,
                'amount' => $this->amount,
                'message' => $this->message,
                'status' => 'pending',
            ]);

            // TODO: Move the API Key to your .env file for security
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Cache-Control' => 'no-cache',
                'Ocp-Apim-Subscription-Key' => env('OCP_APIM_SUBSCRIPTION_KEY'), 
            ])->post('https://api.myumbbank.com/wallettobank/v1/collect', [
                "customerNumber" => $this->formatPhoneNumber($this->number),
                "channel" => $this->network,
                "amount" => (float) $this->amount,
                "callbackUrl" => route('payment.callback'),
                "description" => substr($description, 0, 100),
                "clientReference" => $this->clientReference
            ]);

            $this->response = $response->json();

            // Update transaction with immediate response
            $responseCode = $this->response['responseCode'] ?? $response->status();
            $status = 'failed';

            if ($response->successful() && $responseCode === '0001') {
                $status = 'pending';
            } elseif ($response->successful() && $responseCode === '0000') {
                 $status = 'success';
            }

            $transaction->update([
                'status' => $status,
                'response_code' => $responseCode,
                'response_body' => $this->response,
                'transaction_id' => $this->response['data']['transactionId'] ?? null,
            ]);

            if ($status === 'success') {
                $this->paymentStatus = 'success';
            } elseif ($status === 'pending') {
                $this->paymentStatus = 'processing';
            } else {
                $this->paymentStatus = 'error';
                $this->errorMessage = $this->getFailureMessage($responseCode);
                $this->detailedError = 'Status: ' . $response->status() . ' - Body: ' . $response->body();
            }

        } catch (\Throwable $th) {
            $this->paymentStatus = 'error';
            $this->errorMessage = 'An unexpected error occurred. Please try again later.';
            $this->detailedError = $th->getMessage();
            $this->response = ['error' => $th->getMessage()];
        }
    }

    public function checkTransactionStatus()
    {
        if (! $this->clientReference) {
            return;
        }

        $transaction = Transaction::where('client_reference', $this->clientReference)->first();

        if ($transaction) {
            if ($transaction->status === 'success') {
                $this->paymentStatus = 'success';
            } elseif ($transaction->status === 'failed') {
                $this->paymentStatus = 'error';
                $this->errorMessage = $this->getFailureMessage($transaction->response_code);
            }
        }
    }

    private function getFailureMessage($code)
    {
        $failureMap = [
            '0005' => 'Service error. Please contact support if money was deducted.',
            '2001' => 'Transaction failed. Invalid PIN, timeout, or insufficient funds.',
            '2100' => 'The customer phone is switched off.',
            
            // Airtel
            '2101' => 'Invalid PIN. Please check and try again.',
            '2102' => 'Insufficient funds in Airtel wallet.',
            '2103' => 'Number not registered on Airtel Money.',
            
            // MTN
            '2050' => 'Insufficient funds in Mobile Money wallet. Please top up.',
            '2051' => 'Number not registered on MTN Mobile Money.',
            
            // Tigo
            '2152' => 'Number not registered on Tigo Cash.',
            '2153' => 'Amount exceeds maximum allowed.',
            '2154' => 'Amount exceeds daily limit.',
            
            // General
            '4000' => 'Validation Error. Please check your details.',
            '4004' => 'Session expired. Please try again.',
        ];

        return $failureMap[$code] ?? 'Payment failed. Please try again or check your details.';
    }

    /**
     * Formats the phone number to include country code if missing.
     *
     * @param string $number The input phone number.
     * @return string The formatted phone number.
     */
    private function formatPhoneNumber($number)
    {
        // Remove spaces and non-numeric characters
        $number = preg_replace('/\D/', '', $number);

        // Assuming Ghana country code +233
        if (str_starts_with($number, '0')) {
            return '233' . substr($number, 1);
        } 
        
        if (str_starts_with($number, '233')) {
            return $number;
        }

        return '233' . $number;
    }

    public function render()
    {
        return view('livewire.payment-form');
    }

    public function resetForm()
    {
        $this->reset(['name', 'number', 'network', 'amount', 'message', 'response', 'paymentStatus', 'errorMessage', 'detailedError', 'clientReference']);
    }
}
