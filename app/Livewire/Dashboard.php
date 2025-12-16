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
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedNetwork = '';
    public $selectedStatus = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedNetwork()
    {
        $this->resetPage();
    }

    public function updatedSelectedStatus()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

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
        return Transaction::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('client_reference', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_number', 'like', '%' . $this->search . '%')
                        ->orWhere('network', 'like', '%' . $this->search . '%')
                        ->orWhere('status', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedNetwork, function ($query) {
                $network = $this->selectedNetwork;
                if ($network === 'MTN-GH') {
                    $query->where('network', 'like', '%mtn%');
                } elseif ($network === 'VODAFONE-GH') {
                    $query->where(function($q) {
                        $q->where('network', 'like', '%vodafone%')
                          ->orWhere('network', 'like', '%telecel%');
                    });
                } elseif ($network === 'TIGO-GH') {
                    $query->where(function($q) {
                        $q->where('network', 'like', '%tigo%')
                          ->orWhere('network', 'like', '%airtel%');
                    });
                } else {
                    $query->where('network', $network);
                }
            })
            ->when($this->selectedStatus, function ($query) {
                // If status is 'failed', include 'error' status as well
                if ($this->selectedStatus === 'failed') {
                    $query->whereIn('status', ['failed', 'error']);
                } else {
                    $query->where('status', $this->selectedStatus);
                }
            })
            ->orderBy($this->sortField === 'name' ? 'customer_name' : $this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    #[Computed]
    public function rows()
    {
        return $this->paginator->getCollection()->map(function ($transaction) {
            $networkClass = '';
            $network = strtolower($transaction->network);
            
            if (str_contains($network, 'mtn')) {
                $networkClass = 'bg-yellow-100 text-yellow-800 border-yellow-200';
            } elseif (str_contains($network, 'vodafone') || str_contains($network, 'telecel')) {
                $networkClass = 'bg-red-100 text-red-800 border-red-200';
            } elseif (str_contains($network, 'tigo') || str_contains($network, 'airtel')) {
                $networkClass = 'bg-blue-100 text-blue-800 border-blue-200';
            } else {
                $networkClass = 'bg-gray-100 text-gray-800 border-gray-200';
            }

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
                'customer' => $transaction->customer_name,
                'purchase' => ucfirst($transaction->network) . ' (' . $transaction->customer_number . ')',
                'amount' => 'GH₵ ' . number_format($transaction->amount, 2),
                'network' => strtoupper($transaction->network),
                'network_class' => $networkClass,
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

    public $showExportModal = false;
    public $exportNetwork = '';
    public $exportStatus = '';

    public function openExportModal()
    {
        $this->showExportModal = true;
    }

    public function closeExportModal()
    {
        $this->showExportModal = false;
        $this->exportNetwork = '';
        $this->exportStatus = '';
    }

    public function export()
    {
        return response()->streamDownload(function () {
            $csv = fopen('php://output', 'w');
            
            // Header
            fputcsv($csv, ['Date', 'Customer', 'Phone', 'Network', 'Status', 'Amount', 'Reference', 'Message']);

            // Query
            $query = Transaction::query()->orderBy('created_at', 'desc');

            // Apply Network Filter
            if ($this->exportNetwork) {
                 $network = $this->exportNetwork;
                 if ($network === 'MTN-GH') {
                    $query->where('network', 'like', '%mtn%');
                } elseif ($network === 'VODAFONE-GH') {
                    $query->where(function($q) {
                        $q->where('network', 'like', '%vodafone%')
                          ->orWhere('network', 'like', '%telecel%');
                    });
                } elseif ($network === 'TIGO-GH') {
                    $query->where(function($q) {
                        $q->where('network', 'like', '%tigo%')
                          ->orWhere('network', 'like', '%airtel%');
                    });
                } else {
                    $query->where('network', $network);
                }
            }

            // Apply Status Filter
            if ($this->exportStatus) {
                if ($this->exportStatus === 'failed') {
                    $query->whereIn('status', ['failed', 'error']);
                } else {
                    $query->where('status', $this->exportStatus);
                }
            }

            $query->chunk(1000, function ($transactions) use ($csv) {
                foreach ($transactions as $transaction) {
                    fputcsv($csv, [
                        $transaction->created_at->format('Y-m-d H:i:s'),
                        $transaction->customer_name,
                        $transaction->customer_number,
                        $transaction->network,
                        $transaction->status,
                        $transaction->amount,
                        $transaction->client_reference,
                        $transaction->message
                    ]);
                }
            });

            fclose($csv);
        }, 'transactions-export-' . now()->format('Y-m-d-His') . '.csv');
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
