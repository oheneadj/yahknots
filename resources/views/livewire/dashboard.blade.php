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
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Recent Transactions</h3>
            <a href="#" class="text-sm text-primary font-medium hover:underline">See All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                        <th class="py-3 pl-2 font-medium">Customer</th>
                        <th class="py-3 font-medium">Network</th>
                        <th class="py-3 font-medium max-md:hidden">Status</th>
                        <th class="py-3 pr-2 font-medium text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach ($this->rows as $row)
                        <tr class="group hover:bg-gray-50 transition-colors cursor-pointer"
                            wire:click="viewTransaction({{ $row['id'] }})">
                            <td class="py-4 pl-2 flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-xs font-semibold">
                                    {{ substr($row['customer'], 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-900 block">{{ $row['customer'] }}</span>
                                    <span class="text-xs text-gray-500">{{ $row['date'] }}</span>
                                </div>
                            </td>
                            <td class="py-4">
                                @php
                                    $network = strtolower($row['original']->network ?? '');
                                    $networkLabel = 'N/A';
                                    $networkBg = '';
                                    $networkText = '';

                                    if (str_contains($network, 'mtn')) {
                                        $networkLabel = 'MTN';
                                        $networkBg = 'style="background-color: #fed8c2; color: #8b5a3c;"';
                                    } elseif (str_contains($network, 'vodafone')) {
                                        $networkLabel = 'TELECEL';
                                        $networkBg = 'style="background-color: #586f5b; color: #fff;"';
                                    } elseif (str_contains($network, 'tigo') || str_contains($network, 'airteltigo')) {
                                        $networkLabel = 'TIGO';
                                        $networkBg = 'style="background-color: #c8916d; color: #fff;"';
                                    } else {
                                        $networkBg = 'style="background-color: #f3f4f6; color: #374151;"';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" {!! $networkBg !!}>
                                    {{ $networkLabel }}
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
                                <div class="flex items-center justify-end gap-2">
                                    <span class="font-semibold text-gray-900">{{ $row['amount'] }}</span>
                                    <button
                                        class="opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-primary">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
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
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-60 backdrop-blur-sm" 
                     wire:click="closeModal"
                     x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                </div>

                <!-- Modal panel -->
                <div
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                    x-show="show"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    @if($selectedTransaction)
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-5">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-white mb-1">Transaction Details</h3>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="text-blue-100 text-sm font-mono">
                                            #{{ $selectedTransaction->client_reference }}
                                        </p>
                                        <span 
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize shadow-sm
                                            {{ $selectedTransaction->status === 'success' ? 'bg-green-500 text-white' : ($selectedTransaction->status === 'pending' ? 'bg-amber-500 text-white' : 'bg-red-500 text-white') }}">
                                            <i class="fa-solid fa-circle text-[6px] mr-1.5"></i>
                                            {{ $selectedTransaction->status }}
                                        </span>
                                    </div>
                                </div>
                                <button wire:click="closeModal" 
                                        class="text-white/80 hover:text-white transition-colors p-1">
                                    <i class="fa-solid fa-xmark text-2xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="px-6 py-6 space-y-5">
                            <!-- Customer Info -->
                            <div class="bg-gray-50 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-lg font-bold flex-shrink-0">
                                        {{ substr($selectedTransaction->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-500 mb-0.5">Customer</p>
                                        <p class="text-base font-bold text-gray-900 truncate">{{ $selectedTransaction->name }}</p>
                                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
                                            <i class="fa-solid fa-calendar"></i>
                                            <span>{{ $selectedTransaction->created_at->format('M d, Y') }}</span>
                                            <span class="text-gray-300">•</span>
                                            <i class="fa-solid fa-clock"></i>
                                            <span>{{ $selectedTransaction->created_at->format('h:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Amount Card -->
                            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl p-5 border border-emerald-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-emerald-600 font-medium mb-1">Amount Paid</p>
                                        <p class="text-3xl font-bold text-emerald-700">
                                            GH₵ {{ number_format($selectedTransaction->amount, 2) }}
                                        </p>
                                    </div>
                                    <div class="w-14 h-14 bg-emerald-200 rounded-full flex items-center justify-center">
                                        <i class="fa-solid fa-money-bill-wave text-emerald-700 text-xl"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Details -->
                            <div class="space-y-3">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Payment Details</h4>
                                <div class="bg-white border border-gray-200 rounded-xl divide-y divide-gray-100">
                                    <div class="flex justify-between items-center px-4 py-3">
                                        <span class="text-sm text-gray-600 flex items-center gap-2">
                                            <i class="fa-solid fa-mobile-screen text-gray-400"></i>
                                            Phone Number
                                        </span>
                                        <span class="text-sm font-semibold text-gray-900">{{ $selectedTransaction->number }}</span>
                                    </div>
                                    <div class="flex justify-between items-center px-4 py-3">
                                        <span class="text-sm text-gray-600 flex items-center gap-2">
                                            <i class="fa-solid fa-network-wired text-gray-400"></i>
                                            Network
                                        </span>
                                        @php
                                            $network = strtolower($selectedTransaction->network ?? '');
                                            $networkLabel = 'N/A';
                                            $networkBg = '';

                                            if (str_contains($network, 'mtn')) {
                                                $networkLabel = 'MTN';
                                                $networkBg = 'style="background-color: #fed8c2; color: #8b5a3c;"';
                                            } elseif (str_contains($network, 'vodafone')) {
                                                $networkLabel = 'TELECEL';
                                                $networkBg = 'style="background-color: #586f5b; color: #fff;"';
                                            } elseif (str_contains($network, 'tigo') || str_contains($network, 'airteltigo')) {
                                                $networkLabel = 'TIGO';
                                                $networkBg = 'style="background-color: #c8916d; color: #fff;"';
                                            } else {
                                                $networkBg = 'style="background-color: #f3f4f6; color: #374151;"';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold" {!! $networkBg !!}>
                                            {{ $networkLabel }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center px-4 py-3">
                                        <span class="text-sm text-gray-600 flex items-center gap-2">
                                            <i class="fa-solid fa-hashtag text-gray-400"></i>
                                            Transaction ID
                                        </span>
                                        <span class="text-sm font-mono font-semibold text-gray-900">{{ $selectedTransaction->id }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Message if exists -->
                            @if($selectedTransaction->message)
                                <div class="bg-gradient-to-br from-orange-50 to-amber-50 border-2 border-orange-200 rounded-xl p-4">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 bg-orange-200 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-heart text-orange-600 text-lg"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-bold text-orange-800 uppercase tracking-wide mb-1.5">
                                                Message to Couple
                                            </p>
                                            <p class="text-sm text-gray-700 leading-relaxed italic">
                                                "{{ $selectedTransaction->message }}"
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Footer -->
                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-100">
                            <button wire:click="closeModal"
                                class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fa-solid fa-check mr-1.5"></i>
                                Close
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>