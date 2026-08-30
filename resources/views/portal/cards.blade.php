<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">My Cards</h2>
    </x-slot>

    @php $needConfirming = $cards->whereNull('still_open')->count(); @endphp

    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 p-6 mb-6">
        <p class="text-white font-medium">You have {{ $cards->count() }} card(s).</p>
        <p class="text-indigo-100 text-sm mt-1">{{ $needConfirming }} still need a Yes or No on whether they're still open.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse ($cards as $card)
            @php
                $spotsSold = $card->auAdds->count();
                $spotsFree = max(($card->slots_total ?? 0) - $spotsSold, 0);
                $nextStep = is_null($card->still_open)
                    ? "Say whether {$card->issuer_name} is still open."
                    : (is_null($card->balance_date) ? "Write today's balance." : 'Nothing needed right now.');
            @endphp
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800">{{ $card->issuer_name }}</p>
                            <p class="text-xs text-slate-400">{{ $card->card_code }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset
                        {{ is_null($card->still_open) ? 'bg-amber-50 text-amber-700 ring-amber-600/20' : ($card->still_open ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 'bg-rose-50 text-rose-700 ring-rose-600/20') }}">
                        {{ is_null($card->still_open) ? 'Needs answer' : ($card->still_open ? 'Open' : 'Closed') }}
                    </span>
                </div>
                <dl class="grid grid-cols-2 gap-3 text-sm mb-3">
                    <div><dt class="text-xs text-slate-400">Credit Limit</dt><dd class="font-medium text-slate-700">${{ number_format($card->credit_limit, 0) }}</dd></div>
                    <div><dt class="text-xs text-slate-400">Card Opened</dt><dd class="font-medium text-slate-700">{{ $card->opened_date?->format('M j, Y') ?? '—' }}</dd></div>
                    <div><dt class="text-xs text-slate-400">Spots Sold / Free</dt><dd class="font-medium text-slate-700">{{ $spotsSold }} / {{ $spotsFree }}</dd></div>
                    <div><dt class="text-xs text-slate-400">Pay Per Spot</dt><dd class="font-medium text-slate-700">{{ $card->pay_per_spot !== null ? '$'.number_format($card->pay_per_spot, 2) : '—' }}</dd></div>
                    <div><dt class="text-xs text-slate-400">Balance Today</dt><dd class="font-medium text-slate-700">{{ $card->balance !== null ? '$'.number_format($card->balance, 0) : '—' }}</dd></div>
                    <div><dt class="text-xs text-slate-400">Balance Date</dt><dd class="font-medium text-slate-700">{{ $card->balance_date?->format('M j, Y') ?? '—' }}</dd></div>
                </dl>
                <p class="text-xs text-slate-500 pt-3 border-t border-slate-100">{{ $nextStep }}</p>
            </div>
        @empty
            <p class="text-slate-500 col-span-2 text-center py-10">No cards yet.</p>
        @endforelse
    </div>
</x-app-layout>