<div class="flex flex-col gap-6">
    <!-- Filter Bar (Matches Reference) -->
    <div class="flex justify-between items-center mb-6">
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

                <flux:badge as="button" variant="pill" color="zinc" icon="plus" size="lg">Amount</flux:badge>
                <flux:badge as="button" variant="pill" color="zinc" icon="plus" size="lg" class="max-md:hidden">
                    Status</flux:badge>
                <flux:badge as="button" variant="pill" color="zinc" icon="plus" size="lg">More filters...
                </flux:badge>
            </div>
        </div>

        <div class="w-auto! ml-2 flex gap-1">
            <flux:button icon="list-bullet" variant="subtle" size="sm" />
            <flux:button icon="squares-2x2" variant="ghost" size="sm" />
        </div>
    </div>

    <!-- Stats Grid (Matches Reference Loop) -->
    <div class="flex flex-col md:flex-row gap-6 mb-6">
        @foreach ($stats as $stat)
            <div
                class="relative flex-1 rounded-lg px-6 py-4 bg-zinc-50 dark:bg-zinc-700/[.25] border border-zinc-200 dark:border-zinc-700/50 {{ $loop->iteration > 2 ? 'max-md:hidden' : '' }}  {{ $loop->iteration > 4 ? 'max-lg:hidden' : '' }}">
                <flux:subheading>{{ $stat['title'] }}</flux:subheading>

                <flux:heading size="xl" class="mb-2">{{ $stat['value'] }}</flux:heading>

                <div
                    class="flex items-center gap-1 font-medium text-sm @if ($stat['trendUp']) text-green-600 dark:text-green-400 @else text-red-500 dark:text-red-400 @endif">
                    <flux:icon :icon="$stat['trendUp'] ? 'arrow-trending-up' : 'arrow-trending-down'" variant="micro" />
                    {{ $stat['trend'] }}
                </div>

                <div class="absolute top-0 right-0 pr-2 pt-2">
                    <flux:button icon="ellipsis-horizontal" variant="subtle" size="sm" />
                </div>
            </div>
        @endforeach
    </div>

    <!-- Transactions Table (Matches Reference Style using HTML/Tailwind) -->
    <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700 text-sm">
                <thead>
                    <tr class="">
                        <th class="px-6 py-3 text-left w-10">
                            <flux:checkbox />
                        </th>
                        <th class="px-6 py-3 text-left font-medium text-zinc-500 max-md:hidden">ID</th>
                        <th class="px-6 py-3 text-left font-medium text-zinc-500 max-md:hidden">Date</th>
                        <th class="px-6 py-3 text-left font-medium text-zinc-500 max-md:hidden">Status</th>
                        <th class="px-6 py-3 text-left font-medium text-zinc-500">Customer</th>
                        <th class="px-6 py-3 text-left font-medium text-zinc-500">Network/Number</th>
                        <th class="px-6 py-3 text-left font-medium text-zinc-500">Revenue</th>
                        <th class="px-6 py-3 text-left font-medium text-zinc-500"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <flux:checkbox />
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap font-medium text-zinc-900 dark:text-zinc-100 max-md:hidden">
                                #{{ $transaction->client_reference ?? $transaction->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-500 dark:text-zinc-400 max-md:hidden">
                                {{ $transaction->created_at->format('M d, H:i A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap max-md:hidden">
                                @if($transaction->status === 'success')
                                    <flux:badge color="green" size="sm" inset="top bottom">Paid</flux:badge>
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
                                    <span
                                        class="font-medium text-zinc-900 dark:text-zinc-100 max-md:hidden">{{ $transaction->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-500 dark:text-zinc-400">
                                <span class="capitalize">{{ $transaction->network }}</span> ({{ $transaction->number }})
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-zinc-900 dark:text-zinc-100">
                                GH₵ {{ number_format($transaction->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
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
            <div class="border-t border-zinc-200 dark:border-zinc-700 py-2">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    <!-- Details Modal (Preserved) -->
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