<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Our Account</h2>
    </x-slot>

    @php
        $weOweYou = $entries->where('direction', 'we_owe_you')->whereNull('settled_date')->sum('amount');
        $youOweUs = $entries->where('direction', 'you_owe_us')->whereNull('settled_date')->sum('amount');
        $net = $weOweYou - $youOweUs;
        $allAgreed = $entries->every(fn ($e) => $e->agreed);
    @endphp

    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 p-6 mb-6">
        <p class="text-white font-medium">
            @if ($net > 0)
                We owe you ${{ number_format($net, 2) }} net right now.
            @elseif ($net < 0)
                You owe us ${{ number_format(abs($net), 2) }} net right now.
            @else
                You and we are square right now.
            @endif
        </p>
        <p class="text-indigo-100 text-sm mt-1">{{ $allAgreed ? 'Every line is agreed.' : 'Some lines still need your agreement.' }}</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">What For</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Which Way</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Agreed?</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Settled</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($entries as $entry)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-4 py-3 text-slate-500">{{ $entry->entry_date?->format('M j, Y') ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-800">{{ $entry->description }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $entry->direction === 'we_owe_you' ? 'We Owe You' : 'You Owe Us' }}</td>
                            <td class="px-4 py-3 font-medium text-slate-800">${{ number_format($entry->amount, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $entry->agreed ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 'bg-amber-50 text-amber-700 ring-amber-600/20' }}">
                                    {{ $entry->agreed ? 'Yes' : 'Needs agreement' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-500">{{ $entry->settled_date?->format('M j, Y') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">Nothing on the account yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>