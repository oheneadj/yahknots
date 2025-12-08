<div class="flex flex-col gap-6">
    <!-- Filter Bar -->
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-2">
            <div class="flex items-center gap-2">
                <flux:select size="sm" class="w-32">
                    <option selected>Last 30 days</option>
                    <option>Last 60 days</option>
                    <option>Last 90 days</option>
                </flux:select>

                <flux:subheading class="max-md:hidden whitespace-nowrap">compared to</flux:subheading>

                <flux:select size="sm" class="max-md:hidden w-32">
                    <option selected>Previous period</option>
                    <option>Last month</option>
                </flux:select>
            </div>

            <flux:separator vertical class="max-lg:hidden mx-2 my-2" />

            <div class="max-lg:hidden flex justify-start items-center gap-2">
                <flux:subheading class="whitespace-nowrap">Filter by:</flux:subheading>

                <flux:badge as="button" icon="plus" size="sm">Amount</flux:badge>
                <flux:badge as="button" icon="plus" size="sm" class="max-md:hidden">Status</flux:badge>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Card 1: Total Received -->
        <div
            class="relative flex-1 rounded-lg px-6 py-4 bg-zinc-50 dark:bg-zinc-700/50 border border-zinc-200 dark:border-zinc-700">
            <flux:subheading>Total Revenue</flux:subheading>
            <flux:heading size="xl" class="mb-2">GH₵ {{ number_format($totalAmount, 2) }}</flux:heading>
            <div class="flex items-center gap-1 font-medium text-sm text-green-600 dark:text-green-400">
                <flux:icon icon="arrow-trending-up" variant="micro" />
                100%
            </div>
        </div>

        <!-- Card 2: Successful -->
        <div
            class="relative flex-1 rounded-lg px-6 py-4 bg-zinc-50 dark:bg-zinc-700/50 border border-zinc-200 dark:border-zinc-700">
            <flux:subheading>Successful Transactions</flux:subheading>
            <flux:heading size="xl" class="mb-2">{{ $successfulCount }}</flux:heading>
            <div class="flex items-center gap-1 font-medium text-sm text-green-600 dark:text-green-400">
                <flux:icon icon="check-circle" variant="micro" />
                Active
            </div>
        </div>

        <!-- Card 3: Pending -->
        <div
            class="relative flex-1 rounded-lg px-6 py-4 bg-zinc-50 dark:bg-zinc-700/50 border border-zinc-200 dark:border-zinc-700">
            <flux:subheading>Pending Transactions</flux:subheading>
            <flux:heading size="xl" class="mb-2">{{ $pendingCount }}</flux:heading>
            <div class="flex items-center gap-1 font-medium text-sm text-orange-500">
                <flux:icon icon="clock" variant="micro" />
                Waiting
            </div>
        </div>

        <!-- Card 4: Failed -->
        <div
            class="relative flex-1 rounded-lg px-6 py-4 bg-zinc-50 dark:bg-zinc-700/50 border border-zinc-200 dark:border-zinc-700">
            <flux:subheading>Failed Transactions</flux:subheading>
            <flux:heading size="xl" class="mb-2">{{ $failedCount }}</flux:heading>
            <div class="flex items-center gap-1 font-medium text-sm text-red-500">
                <flux:icon icon="x-circle" variant="micro" />
                Errors
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-800/50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contributor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Network</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700 bg-white dark:bg-zinc-800">
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $transaction->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($transaction->status === 'success')
                                    <flux:badge color="green" size="sm" inset="top bottom">Success</flux:badge>
                                @elseif($transaction->status === 'pending')
                                    <flux:badge color="orange" size="sm" inset="top bottom">Pending</flux:badge>
                                @else
                                    <flux:badge color="red" size="sm" inset="top bottom">Failed</flux:badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <flux:avatar
                                        src="https://ui-avatars.com/api/?name={{ $transaction->name }}&background=random"
                                        size="xs" />
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $transaction->name }}</span>
                                        <span class="text-xs text-gray-500">{{ $transaction->number }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 capitalize">
                                {{ $transaction->network }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                                GH₵ {{ number_format($transaction->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <flux:dropdown position="bottom" align="end">
                                    <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom" />
                                    <flux:menu>
                                        <flux:menu.item icon="eye" wire:click="viewTransaction({{ $transaction->id }})">View
                                            Details</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="border-t border-zinc-200 dark:border-zinc-700 p-4">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    <!-- Details Modal -->
    <flux:modal wire:model="showModal" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg" class="mb-1">Transaction Details</flux:heading>
                <flux:subheading>
                    Ref: {{ $selectedTransaction?->client_reference }}
                </flux:subheading>
            </div>

            @if($selectedTransaction)
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-lg space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Amount</span>
                            <span class="font-medium">GH₵ {{ number_format($selectedTransaction->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Transaction Fee</span>
                            <span class="font-medium">GH₵ 1.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">E-Levy</span>
                            <span class="font-medium">GH₵ 1.00</span>
                        </div>
                        <div class="pt-2 border-t border-gray-200 flex justify-between">
                            <span class="font-bold text-gray-900">Total Charged</span>
                            <span class="font-bold text-gray-900">GH₵
                                {{ number_format($selectedTransaction->amount, 2) }}</span>
                        </div>
                    </div>

                    @if($selectedTransaction->message)
                        <div class="bg-orange-50 p-4 rounded-lg border border-orange-100">
                            <p class="text-xs font-bold text-orange-800 uppercase tracking-wide mb-1">
                                MESSAGE TO COUPLE
                            </p>
                            <p class="text-gray-800 italic">
                                "{{ $selectedTransaction->message }}"
                            </p>
                        </div>
                    @endif

                    @if($selectedTransaction->response_body)
                        <div>
                            <p class="text-xs font-bold text-gray-500 mb-1">RAW API RESPONSE</p>
                            <div class="bg-gray-900 text-gray-200 p-3 rounded text-xs font-mono overflow-auto max-h-40">
                                <pre>{{ json_encode($selectedTransaction->response_body, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <div class="flex justify-end">
                <flux:button wire:click="closeModal">Close</flux:button>
            </div>
        </div>
    </flux:modal>
</div>