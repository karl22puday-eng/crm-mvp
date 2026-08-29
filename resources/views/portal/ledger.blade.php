<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Our Account</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @php
                $weOweYou = $entries->where('direction', 'we_owe_you')->whereNull('settled_date')->sum('amount');
                $youOweUs = $entries->where('direction', 'you_owe_us')->whereNull('settled_date')->sum('amount');
                $net = $weOweYou - $youOweUs;
                $allAgreed = $entries->every(fn ($e) => $e->agreed);
            @endphp

            <div class="bg-indigo-600 text-white rounded-lg p-4 mb-6">
                @if ($net > 0)
                    We owe you ${{ number_format($net, 2) }} net right now.
                @elseif ($net < 0)
                    You owe us ${{ number_format(abs($net), 2) }} net right now.
                @else
                    You and we are square. Nothing settles this month.
                @endif
                — {{ $allAgreed ? 'Every line is agreed.' : 'Some lines still need your agreement.' }}
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm whitespace-nowrap">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">What It Was For</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Which Way</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Agreed?</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Settled On</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Raised By</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($entries as $i => $entry)
                            <tr>
                                <td class="px-3 py-3 text-gray-400">{{ $i + 1 }}</td>
                                <td class="px-3 py-3">{{ $entry->entry_date?->format('M j, Y') ?? '—' }}</td>
                                <td class="px-3 py-3">{{ $entry->description }}</td>
                                <td class="px-3 py-3 text-gray-500">{{ $entry->type ?? '—' }}</td>
                                <td class="px-3 py-3">{{ $entry->direction === 'we_owe_you' ? 'We Owe You' : 'You Owe Us' }}</td>
                                <td class="px-3 py-3 font-medium">${{ number_format($entry->amount, 2) }}</td>
                                <td class="px-3 py-3">
                                    <span class="{{ $entry->agreed ? 'text-green-600' : 'text-amber-600' }}">
                                        {{ $entry->agreed ? 'Yes' : 'Needs your agreement' }}
                                    </span>
                                </td>
                                <td class="px-3 py-3">{{ $entry->settled_date?->format('M j, Y') ?? '—' }}</td>
                                <td class="px-3 py-3 text-gray-500">{{ $entry->raised_by ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-3 py-4 text-center text-gray-500">Nothing on the account yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>