<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">My Payments</h2>
    </x-slot>

    @php $needConfirming = $payments->whereNull('confirmed')->count(); @endphp

    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 p-6 mb-6">
        <p class="text-white font-medium">We have sent you {{ $payments->count() }} payment(s).</p>
        <p class="text-indigo-100 text-sm mt-1">{{ $needConfirming }} still need a Yes or No from you.</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="divide-y divide-slate-100">
            @forelse ($payments as $payment)
                @php
                    $nextStep = is_null($payment->confirmed)
                        ? 'Tell us this payment arrived.'
                        : ($payment->confirmed ? 'Nothing needed — confirmed.' : 'Flagged as not received.');
                @endphp
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center gap-4">
                        <div class="h-9 w-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" /></svg>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800">${{ number_format($payment->amount, 2) }}</p>
                            <p class="text-xs text-slate-400">{{ $payment->payment_date?->format('M j, Y') ?? '—' }} · {{ $payment->covers ?? 'No note' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset
                            {{ is_null($payment->confirmed) ? 'bg-amber-50 text-amber-700 ring-amber-600/20' : ($payment->confirmed ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 'bg-rose-50 text-rose-700 ring-rose-600/20') }}">
                            {{ is_null($payment->confirmed) ? 'Needs answer' : ($payment->confirmed ? 'Confirmed' : 'Not received') }}
                        </span>
                        <p class="text-xs text-slate-400 mt-1">{{ $nextStep }}</p>
                    </div>
                </div>
            @empty
                <p class="px-6 py-10 text-center text-slate-500">No payments yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>