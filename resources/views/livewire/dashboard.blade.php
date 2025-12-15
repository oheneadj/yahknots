<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        @foreach ($this->stats as $index => $stat)
            <div
                class="bg-white p-5 md:p-6 rounded-2xl border border-gray-100 shadow-soft hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="flex items-center gap-2 text-gray-500 text-xs md:text-sm font-medium">
                        @if($index === 0)
                            <i class="fa-solid fa-sack-dollar text-emerald-500"></i>
                        @elseif($index === 1)
                            <i class="fa-solid fa-chart-line text-blue-500"></i>
                        @elseif($index === 2)
                            <i class="fa-solid fa-check-circle text-green-500"></i>
                        @else
                            <i class="fa-solid fa-clock text-amber-500"></i>
                        @endif
                        <span class="truncate">{{ $stat['title'] }}</span>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight">{{ $stat['value'] }}</h3>
                    <span
                        class="inline-flex items-center gap-1.5 px-2 py-0.5 {{ $stat['trendUp'] ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }} text-xs font-semibold rounded-full w-fit">
                        <i class="fa-solid fa-arrow-trend-{{ $stat['trendUp'] ? 'up' : 'down' }}"></i>
                        <span class="truncate">{{ $stat['trend'] }}</span>
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Transactions Table -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-soft">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h3 class="text-lg font-bold text-gray-900">Recent Transactions</h3>

            <!-- Search Bar -->
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition duration-150 ease-in-out"
                    placeholder="Search transactions...">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                        <th class="py-3 pl-2 font-medium cursor-pointer hover:text-gray-600"
                            wire:click="sortBy('name')">
                            Customer
                            @if($sortField === 'name')
                                <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </th>
                        <th class="py-3 font-medium cursor-pointer hover:text-gray-600" wire:click="sortBy('network')">
                            Network
                            @if($sortField === 'network')
                                <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </th>
                        <th class="py-3 font-medium cursor-pointer hover:text-gray-600 max-md:hidden"
                            wire:click="sortBy('status')">
                            Status
                            @if($sortField === 'status')
                                <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </th>
                        <th class="py-3 pr-2 font-medium text-right cursor-pointer hover:text-gray-600"
                            wire:click="sortBy('amount')">
                            Amount
                            @if($sortField === 'amount')
                                <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </th>
                        <th class="py-3 font-medium text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse ($this->rows as $row)
                        <tr class="group hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-none">
                            <td class="py-4 pl-2">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-xs font-semibold shrink-0">
                                        {{ substr($row['customer'], 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span
                                            class="font-semibold text-gray-900 block truncate max-w-[150px]">{{ $row['customer'] }}</span>
                                        <span class="text-xs text-gray-500">{{ $row['date'] }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $row['network_class'] }}">
                                    {{ $row['network'] }}
                                </span>
                            </td>
                            <td class="py-4 max-md:hidden">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $row['status_color'] === 'green' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $row['status_color'] === 'orange' ? 'bg-orange-100 text-orange-800' : '' }}
                                            {{ $row['status_color'] === 'red' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $row['status'] }}
                                </span>
                            </td>
                            <td class="py-4 pr-2 text-right">
                                <span class="font-semibold text-gray-900">{{ $row['amount'] }}</span>
                            </td>
                            <td class="py-4 text-right">
                                <button wire:click="viewTransaction({{ $row['id'] }})"
                                    class="text-xs font-medium text-primary hover:text-primary/80 bg-primary/10 hover:bg-primary/20 px-3 py-1.5 rounded-lg transition-colors">
                                    Details
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-inbox text-3xl mb-3 text-gray-300"></i>
                                    <p>No transactions found matching "{{ $search }}"</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 pt-4 border-t border-gray-100">
            {{ $this->paginator->links() }}
        </div>
    </div>

    <!-- Transaction Details Modal -->
    @if($showModal)
        <div class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Background backdrop, show/hide based on modal state. -->
            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-md transition-opacity" 
                 wire:click="closeModal"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <!-- Modal panel -->
                    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        
                        @if($selectedTransaction)
                            <!-- Header -->
                            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-5 border-b border-slate-700">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="text-xl font-bold text-white">Transaction Details</h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize
                                                {{ $selectedTransaction->status === 'success' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 
                                                   ($selectedTransaction->status === 'pending' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 
                                                   'bg-red-500/20 text-red-400 border border-red-500/30') }}">
                                                <i class="fa-solid fa-circle text-[6px] mr-1.5"></i>
                                                {{ $selectedTransaction->status }}
                                            </span>
                                        </div>
                                        <p class="text-slate-400 text-sm font-mono flex items-center gap-2">
                                            <i class="fa-solid fa-hashtag text-slate-500"></i>
                                            {{ $selectedTransaction->client_reference }}
                                        </p>
                                    </div>
                                    <button wire:click="closeModal" 
                                            class="text-slate-400 hover:text-white transition-colors p-2 hover:bg-white/10 rounded-lg">
                                        <i class="fa-solid fa-xmark text-xl"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="max-h-[80vh] overflow-y-auto">
                                <div class="px-6 py-6 space-y-6">
                                    
                                    <!-- Core Info Grid -->
                                    <div class="grid grid-cols-1 gap-6">
                                        <!-- Amount Card -->
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex items-center justify-between">
                                            <div>
                                                <label class="text-xs text-gray-500 block">Amount Paid</label>
                                                <p class="text-3xl font-bold text-emerald-600">GH₵ {{ number_format($selectedTransaction->amount, 2) }}</p>
                                            </div>
                                            <div class="text-right">
                                                <label class="text-xs text-gray-500 block">Date</label>
                                                <p class="text-xs font-semibold text-gray-900">{{ $selectedTransaction->created_at->format('M d, Y') }}</p>
                                                <p class="text-xs text-gray-500">{{ $selectedTransaction->created_at->format('h:i A') }}</p>
                                            </div>
                                        </div>

                                        <!-- Customer & Payment Info Combined -->
                                        <div class="space-y-4">
                                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">Details</h4>
                                            
                                            <div class="grid grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    <label class="text-xs text-gray-500 block mb-1">Customer Name</label>
                                                    <p class="font-bold text-gray-900">{{ $selectedTransaction->customer_name }}</p>
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-500 block mb-1">Phone Number</label>
                                                    <p class="font-mono text-gray-700">{{ $selectedTransaction->customer_number }}</p>
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-500 block mb-1">Network</label>
                                                    @php
                                                        $network = strtolower($selectedTransaction->network ?? '');
                                                        $networkLabel = 'N/A';
                                                        $networkClass = '';

                                                        if (str_contains($network, 'mtn')) {
                                                            $networkLabel = 'MTN';
                                                            $networkClass = 'text-yellow-700 bg-yellow-50 border-yellow-200';
                                                        } elseif (str_contains($network, 'vodafone') || str_contains($network, 'telecel')) {
                                                            $networkLabel = 'TELECEL';
                                                            $networkClass = 'text-red-700 bg-red-50 border-red-200';
                                                        } elseif (str_contains($network, 'tigo') || str_contains($network, 'airtel')) {
                                                            $networkLabel = 'TIGO';
                                                            $networkClass = 'text-blue-700 bg-blue-50 border-blue-200';
                                                        } else {
                                                            $networkClass = 'text-gray-700 bg-gray-50 border-gray-200';
                                                        }
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold border {{ $networkClass }}">
                                                        {{ $networkLabel }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-500 block mb-1">Transaction ID</label>
                                                    <p class="font-mono text-xs text-gray-600 truncate" title="{{ $selectedTransaction->transaction_id }}">
                                                        {{ $selectedTransaction->transaction_id ?? 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Message -->
                                    @if($selectedTransaction->message)
                                        <div class="bg-gradient-to-br from-orange-50 to-amber-50 border border-orange-100 rounded-xl p-4">
                                            <h4 class="text-xs font-bold text-orange-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                                                <i class="fa-solid fa-heart"></i> Message
                                            </h4>
                                            <p class="text-gray-800 italic text-sm leading-relaxed">
                                                "{{ $selectedTransaction->message }}"
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Technical Details Section -->
                                    <div class="border-t border-gray-100 pt-6">
                                        <div x-data="{ expanded: false }">
                                            <button @click="expanded = !expanded" 
                                                    class="flex items-center justify-between w-full text-left bg-slate-50 hover:bg-slate-100 px-4 py-3 rounded-lg transition-colors border border-slate-200 group">
                                                <span class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                                                    <i class="fa-solid fa-code text-slate-400 group-hover:text-slate-600"></i>
                                                    Technical Details
                                                </span>
                                                <i class="fa-solid fa-chevron-down text-slate-400 transition-transform duration-200"
                                                   :class="{ 'rotate-180': expanded }"></i>
                                            </button>

                                            <div x-show="expanded" 
                                                 x-collapse
                                                 class="mt-2 space-y-4 px-1">
                                                
                                                <!-- Response Code -->
                                                <div class="flex items-center gap-4 bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                                    <span class="text-xs font-mono text-gray-500 uppercase">Response Code</span>
                                                    <span class="text-sm font-mono font-bold {{ $selectedTransaction->response_code == '0000' || $selectedTransaction->response_code == '200' ? 'text-green-600' : 'text-slate-700' }}">
                                                        {{ $selectedTransaction->response_code ?? 'NULL' }}
                                                    </span>
                                                </div>

                                                <!-- Response Body -->
                                                <div class="bg-[#1e1e1e] rounded-lg overflow-hidden border border-slate-800 shadow-sm">
                                                    <div class="bg-[#2d2d2d] px-4 py-2 flex justify-between items-center border-b border-white/10">
                                                        <span class="text-xs text-gray-400 font-mono">Response Body (JSON)</span>
                                                        <button @click="navigator.clipboard.writeText($refs.jsonCode.innerText)" 
                                                                class="text-xs text-gray-500 hover:text-white transition-colors">
                                                            <i class="fa-regular fa-copy mr-1"></i> Copy
                                                        </button>
                                                    </div>
                                                    <div class="p-4 overflow-x-auto">
                                                        <pre class="text-xs font-mono leading-relaxed" x-ref="jsonCode"><code class="language-json text-blue-300">@if($selectedTransaction->response_body)@json($selectedTransaction->response_body, JSON_PRETTY_PRINT)@else<span class="text-gray-500">// No response body available</span>@endif</code></pre>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-100">
                                <button wire:click="closeModal"
                                    class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">
                                    Close
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>