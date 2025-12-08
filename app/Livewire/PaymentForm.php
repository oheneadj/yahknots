<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class PaymentForm extends Component
{
    public $name;
    public $number;
    public $network;
    public $amount;
    public $message;
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
        if ($this->message) {
            $description .= ": " . $this->message;
        }

        try {
            // TODO: Move the API Key to your .env file for security
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Cache-Control' => 'no-cache',
                'Ocp-Apim-Subscription-Key' => env('OCP_APIM_SUBSCRIPTION_KEY'), 
            ])->post('https://api.myumbbank.com/wallettobank/v1/collect', [
                "customerNumber" => $this->formatPhoneNumber($this->number),
                "channel" => $this->network,
                "amount" => (float) $this->amount,
                "callbackUrl" => "https://webhook.site/c847b68b-a3db-4c9a-8f8c-c895d1d463b4",
                "description" => substr($description, 0, 100),
                "clientReference" => uniqid('UMB_', true)
            ]);

            $this->response = $response->json();

            if ($response->successful() && isset($this->response['responseCode']) && $this->response['responseCode'] === '0001') {
                $this->paymentStatus = 'success';
            } else {
                $this->paymentStatus = 'error';
                $this->errorMessage = 'Payment failed. Please try again or check your details.';
                $this->detailedError = 'Status: ' . $response->status() . ' - Body: ' . $response->body();
                 Illuminate\Support\Facades\Log::error('Payment failed', ['response' => $response->body()]);
            }

        } catch (\Throwable $th) {
            $this->paymentStatus = 'error';
            $this->errorMessage = 'An unexpected error occurred. Please try again later.';
            $this->detailedError = $th->getMessage();
            $this->response = ['error' => $th->getMessage()];
        }
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
        $this->reset(['name', 'number', 'network', 'amount', 'message', 'response', 'paymentStatus', 'errorMessage', 'detailedError']);
    }
}
