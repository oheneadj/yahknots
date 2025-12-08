<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;

class Dashboard extends Component
{
    use WithPagination;

    public $stats = [];

    public $showModal = false;
    public $selectedTransaction = null;

    public function mount()
    {
        $this->calculateStats();
    }

    public function calculateStats()
    {
        $totalAmount = Transaction::where('status', 'success')->sum('amount');
        $successfulCount = Transaction::where('status', 'success')->count();
        $pendingCount = Transaction::where('status', 'pending')->count();
        $failedCount = Transaction::whereIn('status', ['failed', 'error'])->count();

        // Calculate "Trends" (Mocking mostly as we don't have historical data logic yet, 
        // but ensuring structure matches reference for UI)
        $this->stats = [
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
        return view('livewire.dashboard', [
            'transactions' => Transaction::latest()->paginate(10)
        ]);
    }
}
