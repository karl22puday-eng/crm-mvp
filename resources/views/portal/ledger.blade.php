<x-app-layout>
    <x-slot name="header">
        <h2 class="text-[28px] font-semibold text-white tracking-tight">Our Account</h2>
    </x-slot>

    @php
        $weOweYou = $entries->where('direction', 'we_owe_you')->whereNull('settled_date')->sum('amount');
        $youOweUs = $entries->where('direction', 'you_owe_us')->whereNull('settled_date')->sum('amount');
        $net = $weOweYou - $youOweUs;
        $allAgreed = $entries->every(fn ($e) => $e->agreed);
    @endphp

    <div class="rounded-3xl bg-gradient-to-br from-indigo-500 to-purple-600 p-6 mb-5">
        <p class="text-white font-semibold">
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

    <div class="bg-black/30 backdrop-blur-sm border border-white/10 rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/5 text-sm">
                <thead class="bg-white/[0.03]">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">What For</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Which Way</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Agreed?</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Settled</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($entries as $entry)
                        <tr class="hover:bg-white/[0.03] transition">
                            <td class="px-4 py-3.5 text-slate-500">{{ $entry->entry_date?->format('M j, Y') ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-slate-200">{{ $entry->description }}</td>
                            <td class="px-4 py-3.5 text-slate-500">{{ $entry->direction === 'we_owe_you' ? 'We Owe You' : 'You Owe Us' }}</td>
                            <td class="px-4 py-3.5 font-medium text-slate-200 tabular">${{ number_format($entry->amount, 2) }}</td>
                            <td class="px-4 py-3.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $entry->agreed ? 'bg-emerald-500/15 text-emerald-300 ring-1 ring-inset ring-emerald-500/30' : 'bg-amber-500/15 text-amber-300 ring-1 ring-inset ring-amber-500/30' }}">
                                    {{ $entry->agreed ? 'Yes' : 'Needs agreement' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-slate-500">{{ $entry->settled_date?->format('M j, Y') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">Nothing on the account yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
