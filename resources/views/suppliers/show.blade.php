<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-indigo-400 to-pink-400 flex items-center justify-center text-white text-xs font-bold shrink-0">
                {{ strtoupper(substr($supplier->name, 0, 2)) }}
            </div>
            <h2 class="text-[28px] font-semibold text-white tracking-tight">{{ $supplier->name }}</h2>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="mb-5 flex items-center gap-2 px-4 py-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-sm rounded-xl">
            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ session('status') }}
        </div>
    @endif

    @php
        $card = 'bg-black/30 backdrop-blur-sm border border-white/10 rounded-3xl';
        $tierStyles = [
            'preferred' => 'bg-emerald-500/15 text-emerald-300 ring-1 ring-inset ring-emerald-500/30',
            'standard' => 'bg-blue-500/15 text-blue-300 ring-1 ring-inset ring-blue-500/30',
            'under_review' => 'bg-amber-500/15 text-amber-300 ring-1 ring-inset ring-amber-500/30',
        ];
        $statusStyles = [
            'active' => 'bg-emerald-500/15 text-emerald-300 ring-1 ring-inset ring-emerald-500/30',
            'onboarding' => 'bg-amber-500/15 text-amber-300 ring-1 ring-inset ring-amber-500/30',
            'closed' => 'bg-slate-500/15 text-slate-400 ring-1 ring-inset ring-slate-500/30',
        ];
        $payoutStyles = [
            'paid' => 'bg-emerald-500/15 text-emerald-300 ring-1 ring-inset ring-emerald-500/30',
            'due' => 'bg-blue-500/15 text-blue-300 ring-1 ring-inset ring-blue-500/30',
            'held' => 'bg-amber-500/15 text-amber-300 ring-1 ring-inset ring-amber-500/30',
            'pending' => 'bg-slate-500/15 text-slate-400 ring-1 ring-inset ring-slate-500/30',
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left column --}}
        <div class="space-y-5">
            <div class="{{ $card }} p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-white">Profile</h3>
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="text-sm text-indigo-300 hover:text-indigo-200">Edit</a>
                </div>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-slate-500">Email</dt><dd class="text-slate-200 font-medium">{{ $supplier->email ?? '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Phone</dt><dd class="text-slate-200 font-medium">{{ $supplier->phone ?? '—' }}</dd></div>
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500">Tier</dt>
                        <dd class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tierStyles[$supplier->tier] ?? $tierStyles['under_review'] }}">
                            {{ ucfirst(str_replace('_', ' ', $supplier->tier)) }}
                        </dd>
                    </div>
                    <div class="flex justify-between"><dt class="text-slate-500">Posting Rate</dt><dd class="text-slate-200 font-medium tabular">{{ $supplier->posting_rate }}%</dd></div>
                </dl>
            </div>

            <div class="{{ $card }} p-6">
                <h3 class="font-semibold text-white mb-4">Overview</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div><p class="text-2xl font-bold text-white tabular">{{ $supplier->cards->count() }}</p><p class="text-xs text-slate-500">Cards</p></div>
                    <div><p class="text-2xl font-bold text-white tabular">{{ $supplier->cards->sum(fn($c) => $c->auAdds->count()) }}</p><p class="text-xs text-slate-500">Spots</p></div>
                    <div><p class="text-2xl font-bold text-white tabular">{{ $supplier->payments->count() }}</p><p class="text-xs text-slate-500">Payments</p></div>
                    <div><p class="text-2xl font-bold text-white tabular">{{ $supplier->applications->count() }}</p><p class="text-xs text-slate-500">Applications</p></div>
                </div>
            </div>
        </div>

        {{-- Right column --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Cards --}}
            <div class="{{ $card }}">
                <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
                    <h3 class="font-semibold text-white">Cards <span class="text-slate-500 font-normal">({{ $supplier->cards->count() }})</span></h3>
                    <a href="{{ route('suppliers.cards.create', $supplier) }}" class="text-sm text-indigo-300 hover:text-indigo-200">+ Add Card</a>
                </div>
                @forelse ($supplier->cards as $c)
                    <div class="px-6 py-4 border-b border-white/5 last:border-b-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-white/5 text-slate-400 flex items-center justify-center shrink-0">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-200">{{ $c->issuer_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $c->card_code }}</p>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusStyles[$c->status] ?? '' }}">{{ ucfirst($c->status) }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                <a href="{{ route('suppliers.cards.auAdds.create', [$supplier, $c]) }}" class="text-indigo-300 hover:text-indigo-200">+ Spot</a>
                                <a href="{{ route('suppliers.cards.edit', [$supplier, $c]) }}" class="text-slate-500 hover:text-slate-300">Edit</a>
                            </div>
                        </div>
                        @if ($c->auAdds->count())
                            <div class="mt-3 ml-11 space-y-1.5">
                                @foreach ($c->auAdds as $auAdd)
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-slate-400">{{ $auAdd->client_name }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $payoutStyles[$auAdd->payout_status] ?? '' }}">{{ ucfirst($auAdd->payout_status) }}</span>
                                            <a href="{{ route('suppliers.cards.auAdds.edit', [$supplier, $c, $auAdd]) }}" class="text-slate-500 hover:text-slate-300 text-xs">Edit</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="px-6 py-8 text-center text-sm text-slate-500">No cards yet.</p>
                @endforelse
            </div>

            {{-- Payments --}}
            <div class="{{ $card }}">
                <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
                    <h3 class="font-semibold text-white">Payments <span class="text-slate-500 font-normal">({{ $supplier->payments->count() }})</span></h3>
                    <a href="{{ route('suppliers.payments.create', $supplier) }}" class="text-sm text-indigo-300 hover:text-indigo-200">+ Record Payment</a>
                </div>
                @forelse ($supplier->payments as $payment)
                    <div class="flex items-center justify-between px-6 py-3.5 border-b border-white/5 last:border-b-0">
                        <div>
                            <span class="text-sm font-medium text-slate-200 tabular">${{ number_format($payment->amount, 2) }}</span>
                            <span class="text-xs text-slate-500 ml-2">{{ $payment->payment_date?->format('M j, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $payment->confirmed ? 'bg-emerald-500/15 text-emerald-300 ring-1 ring-inset ring-emerald-500/30' : 'bg-amber-500/15 text-amber-300 ring-1 ring-inset ring-amber-500/30' }}">
                                {{ $payment->confirmed ? 'Confirmed' : 'Unconfirmed' }}
                            </span>
                            <a href="{{ route('suppliers.payments.edit', [$supplier, $payment]) }}" class="text-sm text-slate-500 hover:text-slate-300">Edit</a>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-8 text-center text-sm text-slate-500">No payments yet.</p>
                @endforelse
            </div>

            {{-- Applications --}}
            <div class="{{ $card }}">
                <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
                    <h3 class="font-semibold text-white">Applications <span class="text-slate-500 font-normal">({{ $supplier->applications->count() }})</span></h3>
                    <a href="{{ route('suppliers.applications.create', $supplier) }}" class="text-sm text-indigo-300 hover:text-indigo-200">+ New Application</a>
                </div>
                @forelse ($supplier->applications as $application)
                    <div class="flex items-center justify-between px-6 py-3.5 border-b border-white/5 last:border-b-0">
                        <span class="text-sm font-medium text-slate-200">Application #{{ $application->id }}</span>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-500/15 text-indigo-300 ring-1 ring-inset ring-indigo-500/30">{{ ucfirst($application->status) }}</span>
                            <a href="{{ route('suppliers.applications.show', [$supplier, $application]) }}" class="text-sm text-slate-500 hover:text-slate-300">View</a>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-8 text-center text-sm text-slate-500">No applications yet.</p>
                @endforelse
            </div>

            {{-- Ledger --}}
            <div class="{{ $card }}">
                <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
                    <h3 class="font-semibold text-white">Ledger <span class="text-slate-500 font-normal">({{ $supplier->ledgerEntries->count() }})</span></h3>
                    <a href="{{ route('suppliers.ledger.create', $supplier) }}" class="text-sm text-indigo-300 hover:text-indigo-200">+ Add Entry</a>
                </div>
                @forelse ($supplier->ledgerEntries as $entry)
                    <div class="flex items-center justify-between px-6 py-3.5 border-b border-white/5 last:border-b-0">
                        <div>
                            <span class="text-sm font-medium text-slate-200 tabular">${{ number_format($entry->amount, 2) }}</span>
                            <span class="text-xs text-slate-500 ml-2">{{ $entry->direction === 'we_owe_you' ? 'We Owe You' : 'You Owe Us' }} — {{ $entry->description }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $entry->agreed ? 'bg-emerald-500/15 text-emerald-300 ring-1 ring-inset ring-emerald-500/30' : 'bg-amber-500/15 text-amber-300 ring-1 ring-inset ring-amber-500/30' }}">
                                {{ $entry->agreed ? 'Agreed' : 'Unagreed' }}
                            </span>
                            <a href="{{ route('suppliers.ledger.edit', [$supplier, $entry]) }}" class="text-sm text-slate-500 hover:text-slate-300">Edit</a>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-8 text-center text-sm text-slate-500">No ledger entries yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>