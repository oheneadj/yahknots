<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Transaction;

class Dashboard extends Component
{
    use WithPagination;

    public $showModal = false;
    public $selectedTransaction = null;

    #[Computed]
    public function stats()
    {
        $totalAmount = Transaction::where('status', 'success')->sum('amount');
        $successfulCount = Transaction::where('status', 'success')->count();
        $pendingCount = Transaction::where('status', 'pending')->count();
        $failedCount = Transaction::whereIn('status', ['failed', 'error'])->count();

        return [
            [
                'title' => 'Total revenue',
                'value' => 'GH₵ ' . number_format($totalAmount, 2),
                'trend' => '100%',
                'trendUp' => true
            ],
            [
                'title' => 'Total transactions',
                'value' => $successfulCount + $pendingCount + $failedCount,
                'trend' => 'Recently updated',
                'trendUp' => true
            ],
            [
                'title' => 'Successful',
                'value' => $successfulCount,
                'trend' => 'Active',
                'trendUp' => true
            ],
            [
                'title' => 'Pending/Failed',
                'value' => $pendingCount + $failedCount,
                'trend' => 'Requires attention',
                'trendUp' => false
            ]
        ];
    }

    #[Computed]
    public function paginator()
    {
        return Transaction::latest()->paginate(10);
    }

    #[Computed]
    public function rows()
    {
        return $this->paginator->getCollection()->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'client_reference' => $transaction->client_reference,
                'date' => $transaction->created_at->format('M d, H:i A'),
                'status' => $transaction->status === 'success' ? 'Paid' : ucfirst($transaction->status),
                'status_color' => match($transaction->status) {
                    'success' => 'green',
                    'pending' => 'orange',
                    default => 'red',
                },
                'customer' => $transaction->name,
                'purchase' => ucfirst($transaction->network) . ' (' . $transaction->number . ')',
                'amount' => 'GH₵ ' . number_format($transaction->amount, 2),
                'original' => $transaction // Keep reference to original model for modal actions
            ];
        });
    }

    public function viewTransaction($id)
    {
        $this->selectedTransaction = Transaction::find($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedTransaction = null;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
