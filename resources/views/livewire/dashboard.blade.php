<div class="space-y-8 font-sans">
    
    <!-- Hero Banner -->


    <!-- Stats Overview (Nexus Style) -->
    <div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach ($this->stats as $index => $stat)
                @php
                    $theme = match($index) {
                        0 => [
                            'icon_bg' => 'bg-purple-100',
                            'icon_text' => 'text-purple-600',
                        ],
                        1 => [
                            'icon_bg' => 'bg-blue-100',
                            'icon_text' => 'text-blue-600',
                        ],
                        2 => [
                            'icon_bg' => 'bg-emerald-100',
                            'icon_text' => 'text-emerald-600',
                        ],
                        default => [
                            'icon_bg' => 'bg-orange-100',
                            'icon_text' => 'text-orange-600',
                        ],
                    };

                    $icon = match($index) {
                        0 => 'fa-sack-dollar',
                        1 => 'fa-chart-pie',
                        2 => 'fa-circle-check',
                        default => 'fa-clock-rotate-left',
                    };
                @endphp
                <div class="bg-white p-6 rounded-md border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                    <!-- Top Row: Icon + Title -->
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full {{ $theme['icon_bg'] }} flex items-center justify-center {{ $theme['icon_text'] }}">
                            <i class="fa-solid {{ $icon }} text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-500">{{ $stat['title'] }}</span>
                    </div>

                    <!-- Value -->
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $stat['value'] }}</h3>

                    <!-- Bottom Row: Trend + /month -->
                    <div class="flex items-center gap-2">
                         <span class="text-xs font-bold {{ $stat['trendUp'] ? 'text-emerald-500' : 'text-rose-500' }}">
                            <i class="fa-solid fa-arrow-trend-{{ $stat['trendUp'] ? 'up' : 'down' }} mr-1"></i>
                            {{ $stat['trend'] }}
                        </span>
                        <span class="text-xs text-gray-400 font-medium">/ month</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Transactions Section (Mimicking "Your Lesson" table) -->
    <div class="bg-white rounded-md p-6 md:p-8 shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Recent Transactions</h3>
                <p class="text-sm text-gray-400 mt-1">Monitor your latest payments</p>
            </div>

            <!-- Search Bar (Pill shape) -->
            <div class="relative w-full md:w-72 group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="block w-full pl-11 pr-4 py-3 border-none bg-gray-50 rounded-full text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all duration-200 shadow-inner"
                    placeholder="Search transaction...">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                        <th class="py-4 pl-4 cursor-pointer hover:text-indigo-600 transition-colors" wire:click="sortBy('name')">
                            Customer / Date
                            @if($sortField === 'name') <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i> @endif
                        </th>
                        <th class="py-4 cursor-pointer hover:text-indigo-600 transition-colors" wire:click="sortBy('network')">
                            Network
                            @if($sortField === 'network') <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i> @endif
                        </th>
                        <th class="py-4 cursor-pointer hover:text-indigo-600 transition-colors max-md:hidden" wire:click="sortBy('status')">
                            Status
                            @if($sortField === 'status') <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i> @endif
                        </th>
                        <th class="py-4 text-right pr-6 cursor-pointer hover:text-indigo-600 transition-colors" wire:click="sortBy('amount')">
                            Amount
                            @if($sortField === 'amount') <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i> @endif
                        </th>
                        <th class="py-4 pr-4 text-center w-24">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse ($this->rows as $row)
                        <tr class="group hover:bg-indigo-50/30 transition-colors border-b border-gray-50 last:border-none">
                            <td class="py-4 pl-4">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-bold border-2 border-white shadow-sm ring-1 ring-gray-100 group-hover:ring-indigo-100 transition-all">
                                            {{ substr($row['customer'], 0, 1) }}
                                        </div>
                                        @if($row['original']->status === 'success')
                                            <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 group-hover:text-indigo-700 transition-colors">{{ $row['customer'] }}</p>
                                        <p class="text-xs text-gray-400 font-medium mt-0.5">{{ $row['date'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4">
                                @php
                                   
                                    $netClass = match($row['network']) {
                                        'MTN-GH' => 'bg-yellow-100 text-yellow-700',
                                        'VODAFONE-GH' => 'bg-red-100 text-red-700',
                                        'TIGO-GH' => 'bg-blue-100 text-blue-700',
                                        default => 'bg-gray-100 text-gray-600'
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold {{ $netClass }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-60"></span>
                                    {{ $row['network'] }}
                                </span> 
                            </td>
                            <td class="py-4 max-md:hidden">
                                <span class="inline-flex font-bold text-xs {{ $row['status'] === 'Paid' ? 'text-green-600 px-2 py-1 bg-green-100 rounded-full' : ($row['status'] === 'Pending' ? 'text-amber-600 px-2 py-1 bg-amber-100 rounded-full' : 'text-red-500 px-2 py-1 bg-red-100 rounded-full') }}">
                                    {{ $row['status'] }}
                                </span>
                            </td>
                            <td class="py-4 text-right pr-6">
                                <span class="font-black text-gray-900 group-hover:text-indigo-700 transition-colors">{{ $row['amount'] }}</span>
                            </td>
                            <td class="py-4 pr-4 text-center">
                                <button wire:click="viewTransaction({{ $row['id'] }})"
                                    class="w-8 h-8 mx-auto rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all flex items-center justify-center shadow-sm hover:shadow-md hover:shadow-indigo-200">
                                    <i class="fa-solid fa-chevron-right text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center">
                                        <i class="fa-solid fa-magnifying-glass text-2xl text-gray-300"></i>
                                    </div>
                                    <p class="font-medium">No transactions found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 pt-6 border-t border-gray-100">
            {{ $this->paginator->links() }}
        </div>
    </div>

    <!-- Transaction Details Modal (Retained Original Improved Logic) -->
    @if($showModal)
        <div class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Background backdrop -->
            <div wire:transition.opacity.duration.300ms class="fixed inset-0 z-40 bg-gray-900/10 backdrop-blur-md transition-opacity" 
                 wire:click="closeModal"></div>

            <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <!-- Modal panel -->
                    <div wire:transition.scale.opacity.duration.300ms class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        
                        @if($selectedTransaction)
                            <!-- Header -->
                            <div class="bg-gradient-to-r from-indigo-900 to-purple-900 px-6 py-6 border-b border-white/10 relative overflow-hidden">
                                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
                                <div class="flex items-start justify-between relative z-10">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h3 class="text-xl font-bold text-white">{{ $selectedTransaction->customer_name }}</h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                                {{ $selectedTransaction->status === 'success' ? 'bg-green-500/20 text-green-300 ring-1 ring-green-500/50' : 
                                                   ($selectedTransaction->status === 'pending' ? 'bg-amber-500/20 text-amber-300 ring-1 ring-amber-500/50' : 
                                                   'bg-red-500/20 text-red-300 ring-1 ring-red-500/50') }}">
                                                {{ $selectedTransaction->status }}
                                            </span>
                                        </div>
                                        <p class="text-indigo-200 text-xs font-mono flex items-center gap-2 opacity-80">
                                            <i class="fa-solid fa-hashtag text-indigo-400"></i>
                                            {{ $selectedTransaction->client_reference }}
                                        </p>
                                    </div>
                                    <button wire:click="closeModal" 
                                            class="text-white/60 hover:text-white transition-colors p-2 hover:bg-white/10 rounded-xl">
                                        <i class="fa-solid fa-xmark text-lg"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="max-h-[80vh] overflow-y-auto">
                                <div class="px-6 py-6 space-y-6">
                                    
                                    <!-- Amount Card -->
                                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-5 border border-emerald-100 flex items-center justify-between">
                                        <div>
                                            <label class="text-xs font-bold text-emerald-600 uppercase tracking-wide">Amount Paid</label>
                                            <p class="text-3xl font-black text-emerald-900 tracking-tight mt-1">GH₵ {{ number_format($selectedTransaction->amount, 2) }}</p>
                                        </div>
                                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                                            <i class="fa-solid fa-check text-emerald-600 text-xl"></i>
                                        </div>
                                    </div>

                                    <!-- Details Grid -->
                                    <div class="space-y-4">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider pb-2 border-b border-gray-100">Information</h4>
                                        
                                        <div class="grid grid-cols-2 gap-y-4 gap-x-6">
                                            <div>
                                                <label class="text-[10px] uppercase font-bold text-gray-400 block mb-1">Customer</label>
                                                <p class="text-sm font-bold text-gray-900">{{ $selectedTransaction->customer_name }}</p>
                                            </div>
                                            <div>
                                                <label class="text-[10px] uppercase font-bold text-gray-400 block mb-1">Phone</label>
                                                <p class="text-sm font-mono font-medium text-gray-700">{{ $selectedTransaction->customer_number }}</p>
                                            </div>
                                            <div>
                                                <label class="text-[10px] uppercase font-bold text-gray-400 block mb-1">Date</label>
                                                <p class="text-sm font-medium text-gray-700">{{ $selectedTransaction->created_at->format('M d, Y h:i A') }}</p>
                                            </div>
                                            <div>
                                                <label class="text-[10px] uppercase font-bold text-gray-400 block mb-1">Network</label>
                                                @php
                                                    $modalNetClass = match($selectedTransaction->network) {
                                                        'MTN-GH' => 'bg-yellow-100 text-yellow-700',
                                                        'VODAFONE-GH' => 'bg-red-100 text-red-700',
                                                        'TIGO-GH' => 'bg-blue-100 text-blue-700',
                                                        default => 'bg-gray-100 text-gray-600'
                                                    };
                                                @endphp
                                                <span class="text-xs font-bold px-2 py-1 rounded inline-block {{ $modalNetClass }}">
                                                    {{ strtoupper($selectedTransaction->network) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Message -->
                                    @if($selectedTransaction->message)
                                        <div class="bg-orange-50/50 border border-orange-100 rounded-2xl p-4">
                                            <div class="flex gap-3">
                                                <i class="fa-solid fa-quote-left text-orange-300 text-sm"></i>
                                                <p class="text-sm text-gray-600 italic leading-relaxed">
                                                    {{ $selectedTransaction->message }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Technical (Collapsible) -->
                                    <div class="border-t border-gray-100 pt-6">
                                        <div x-data="{ expanded: false }">
                                            <button @click="expanded = !expanded" 
                                                    class="flex items-center justify-between w-full text-left bg-gray-50 hover:bg-gray-100 px-4 py-3 rounded-xl transition-colors border border-gray-200 group">
                                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wide flex items-center gap-2">
                                                    <i class="fa-solid fa-code text-gray-400"></i>
                                                    Technical Data
                                                </span>
                                                <i class="fa-solid fa-chevron-down text-gray-400 transition-transform duration-200 text-xs"
                                                   :class="{ 'rotate-180': expanded }"></i>
                                            </button>

                                            <div x-show="expanded" x-collapse class="mt-3 space-y-3">
                                                <div class="flex items-center justify-between bg-white px-3 py-2 rounded-lg border border-gray-100">
                                                    <span class="text-xs text-gray-400 font-mono">Status Code</span>
                                                    <span class="font-mono text-xs font-bold text-gray-700">{{ $selectedTransaction->response_code ?? 'N/A' }}</span>
                                                </div>
                                                @if($selectedTransaction->response_body)
                                                    <div class="relative bg-gray-900 rounded-xl overflow-hidden shadow-inner">
                                                        <div class="absolute top-2 right-2">
                                                             <button @click="navigator.clipboard.writeText($refs.jsonCode.innerText)" 
                                                                    class="text-[10px] text-gray-500 hover:text-white transition-colors uppercase font-bold tracking-wider">
                                                                Copy
                                                            </button>
                                                        </div>
                                                        <pre class="p-4 text-[10px] font-mono text-blue-300 overflow-x-auto leading-relaxed" x-ref="jsonCode">@json($selectedTransaction->response_body, JSON_PRETTY_PRINT)</pre>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            
                            <!-- Modal Footer -->
                            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end">
                                <button wire:click="closeModal" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:scale-105">
                                    Close Details
                                </button>
                            </div>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>