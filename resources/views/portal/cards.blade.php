<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Cards</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @php
                $needConfirming = $cards->whereNull('still_open')->count();
            @endphp

            <div class="bg-indigo-600 text-white rounded-lg p-4 mb-6">
                You have {{ $cards->count() }} card(s). {{ $needConfirming }} still need a Yes or No on whether they're still open.
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm whitespace-nowrap">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Card</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Credit Limit</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Card Opened</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statement Close Day</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Spots Sold</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Spots Free</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">My Pay Per Spot</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Still Open?</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance Today</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance Date</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">What To Do Next</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($cards as $i => $card)
                            @php
                                $spotsSold = $card->auAdds->count();
                                $spotsFree = max(($card->slots_total ?? 0) - $spotsSold, 0);
                                $nextStep = is_null($card->still_open)
                                    ? "Say whether {$card->issuer_name} is still open."
                                    : (is_null($card->balance_date) ? "Write today's balance for {$card->issuer_name}." : 'Nothing needed right now.');
                            @endphp
                            <tr>
                                <td class="px-3 py-3 text-gray-400">{{ $i + 1 }}</td>
                                <td class="px-3 py-3 font-medium">{{ $card->issuer_name }}</td>
                                <td class="px-3 py-3">${{ number_format($card->credit_limit, 0) }}</td>
                                <td class="px-3 py-3">{{ $card->opened_date?->format('M j, Y') ?? '—' }}</td>
                                <td class="px-3 py-3">{{ $card->statement_close_day ?? '—' }}</td>
                                <td class="px-3 py-3">{{ $spotsSold }}</td>
                                <td class="px-3 py-3">{{ $spotsFree }}</td>
                                <td class="px-3 py-3">{{ $card->pay_per_spot !== null ? '$'.number_format($card->pay_per_spot, 2) : '— not set —' }}</td>
                                <td class="px-3 py-3">
                                    @if (is_null($card->still_open))
                                        <span class="text-amber-600">Needs answer</span>
                                    @else
                                        <span class="{{ $card->still_open ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $card->still_open ? 'Y' : 'N' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-3">{{ $card->balance !== null ? '$'.number_format($card->balance, 0) : '—' }}</td>
                                <td class="px-3 py-3">{{ $card->balance_date?->format('M j, Y') ?? '—' }}</td>
                                <td class="px-3 py-3 text-gray-500">{{ $nextStep }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-3 py-4 text-center text-gray-500">No cards yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>