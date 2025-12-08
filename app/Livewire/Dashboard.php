<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;

class Dashboard extends Component
{
    use WithPagination;

    public $totalAmount = 0;
    public $successfulCount = 0;
    public $pendingCount = 0;
    public $failedCount = 0;

    public $showModal = false;
    public $selectedTransaction = null;

    public function mount()
    {
        $this->calculateStats();
    }

    public function calculateStats()
    {
        $this->totalAmount = Transaction::where('status', 'success')->sum('amount');
        $this->successfulCount = Transaction::where('status', 'success')->count();
        $this->pendingCount = Transaction::where('status', 'pending')->count();
        $this->failedCount = Transaction::whereIn('status', ['failed', 'error'])->count();
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
