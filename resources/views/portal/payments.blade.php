<x-app-layout>
    <x-slot name="header">
        <h2 class="text-[28px] font-semibold text-white tracking-tight">My Payments</h2>
    </x-slot>

    @php $needConfirming = $payments->whereNull('confirmed')->count(); @endphp

    <div class="rounded-3xl bg-gradient-to-br from-indigo-500 to-purple-600 p-6 mb-5">
        <p class="text-white font-semibold">We have sent you {{ $payments->count() }} payment(s).</p>
        <p class="text-indigo-100 text-sm mt-1">{{ $needConfirming }} still need a Yes or No from you.</p>
    </div>

    <div class="bg-black/30 backdrop-blur-sm border border-white/10 rounded-3xl overflow-hidden">
        <div class="divide-y divide-white/5">
            @forelse ($payments as $payment)
                @php
                    $nextStep = is_null($payment->confirmed)
                        ? 'Tell us this payment arrived.'
                        : ($payment->confirmed ? 'Nothing needed — confirmed.' : 'Flagged as not received.');
                @endphp
                <div class="flex items-center justify-between px-6 py-4 hover:bg-white/[0.03] transition">
                    <div class="flex items-center gap-4">
                        <div class="h-9 w-9 rounded-lg bg-emerald-500/15 text-emerald-300 flex items-center justify-center shrink-0">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" /></svg>
                        </div>
                        <div>
                            <p class="font-medium text-slate-200 tabular">${{ number_format($payment->amount, 2) }}</p>
                            <p class="text-xs text-slate-500">{{ $payment->payment_date?->format('M j, Y') ?? '—' }} · {{ $payment->covers ?? 'No note' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            {{ is_null($payment->confirmed) ? 'bg-amber-500/15 text-amber-300 ring-1 ring-inset ring-amber-500/30' : ($payment->confirmed ? 'bg-emerald-500/15 text-emerald-300 ring-1 ring-inset ring-emerald-500/30' : 'bg-rose-500/15 text-rose-300 ring-1 ring-inset ring-rose-500/30') }}">
                            {{ is_null($payment->confirmed) ? 'Needs answer' : ($payment->confirmed ? 'Confirmed' : 'Not received') }}
                        </span>
                        <p class="text-xs text-slate-500 mt-1">{{ $nextStep }}</p>
                    </div>
                </div>
            @empty
                <p class="px-6 py-10 text-center text-slate-500">No payments yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
