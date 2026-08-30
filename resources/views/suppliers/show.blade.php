<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white text-xs font-semibold shrink-0">
                {{ strtoupper(substr($supplier->name, 0, 2)) }}
            </div>
            <h2 class="page-title">{{ $supplier->name }}</h2>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="mb-6 flex items-center gap-2 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left column: profile + summary --}}
        <div class="space-y-6">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-slate-800">Profile</h3>
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="text-sm text-indigo-600 hover:underline">Edit</a>
                </div>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Email</dt>
                        <dd class="text-slate-800 font-medium">{{ $supplier->email ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Phone</dt>
                        <dd class="text-slate-800 font-medium">{{ $supplier->phone ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500">Tier</dt>
                        @php
                            $tierStyles = [
                                'preferred' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                'standard' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                'under_review' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                            ];
                        @endphp
                        <dd class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $tierStyles[$supplier->tier] ?? $tierStyles['under_review'] }}">
                            {{ ucfirst(str_replace('_', ' ', $supplier->tier)) }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Posting Rate</dt>
                        <dd class="text-slate-800 font-medium">{{ $supplier->posting_rate }}%</dd>
                    </div>
                </dl>
            </div>

            {{-- Quick counts --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Overview</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-2xl font-semibold text-slate-800">{{ $supplier->cards->count() }}</p>
                        <p class="text-xs text-slate-500">Cards</p>
                    </div>
                    <div>
                        <p class="text-2xl font-semibold text-slate-800">{{ $supplier->cards->sum(fn($c) => $c->auAdds->count()) }}</p>
                        <p class="text-xs text-slate-500">Spots</p>
                    </div>
                    <div>
                        <p class="text-2xl font-semibold text-slate-800">{{ $supplier->payments->count() }}</p>
                        <p class="text-xs text-slate-500">Payments</p>
                    </div>
                    <div>
                        <p class="text-2xl font-semibold text-slate-800">{{ $supplier->applications->count() }}</p>
                        <p class="text-xs text-slate-500">Applications</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right column: sections --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Cards & Spots --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">Cards <span class="text-slate-400 font-normal">({{ $supplier->cards->count() }})</span></h3>
                    <a href="{{ route('suppliers.cards.create', $supplier) }}" class="text-sm text-indigo-600 hover:underline">+ Add Card</a>
                </div>

                @forelse ($supplier->cards as $card)
                    @php
                        $statusStyles = [
                            'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                            'onboarding' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                            'closed' => 'bg-slate-100 text-slate-500 ring-slate-400/20',
                        ];
                    @endphp
                    <div class="px-6 py-4 border-b border-slate-50 last:border-b-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-800">{{ $card->issuer_name }}</p>
                                    <p class="text-xs text-slate-400">{{ $card->card_code }}</p>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $statusStyles[$card->status] ?? '' }}">
                                    {{ ucfirst($card->status) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                <a href="{{ route('suppliers.cards.auAdds.create', [$supplier, $card]) }}" class="text-indigo-600 hover:underline">+ Spot</a>
                                <a href="{{ route('suppliers.cards.edit', [$supplier, $card]) }}" class="text-slate-400 hover:text-slate-600">Edit</a>
                            </div>
                        </div>

                        @if ($card->auAdds->count())
                            <div class="mt-3 ml-11 space-y-1.5">
                                @foreach ($card->auAdds as $auAdd)
                                    @php
                                        $payoutStyles = [
                                            'paid' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                            'due' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                            'held' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                            'pending' => 'bg-slate-100 text-slate-500 ring-slate-400/20',
                                        ];
                                    @endphp
                                    <div class="flex items-center justify-between text-sm py-1">
                                        <span class="text-slate-600">{{ $auAdd->client_name }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $payoutStyles[$auAdd->payout_status] ?? '' }}">
                                                {{ ucfirst($auAdd->payout_status) }}
                                            </span>
                                            <a href="{{ route('suppliers.cards.auAdds.edit', [$supplier, $card, $auAdd]) }}" class="text-slate-400 hover:text-slate-600 text-xs">Edit</a>
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
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">Payments <span class="text-slate-400 font-normal">({{ $supplier->payments->count() }})</span></h3>
                    <a href="{{ route('suppliers.payments.create', $supplier) }}" class="text-sm text-indigo-600 hover:underline">+ Record Payment</a>
                </div>
                @forelse ($supplier->payments as $payment)
                    <div class="flex items-center justify-between px-6 py-3 border-b border-slate-50 last:border-b-0">
                        <div>
                            <span class="text-sm font-medium text-slate-800">${{ number_format($payment->amount, 2) }}</span>
                            <span class="text-xs text-slate-400 ml-2">{{ $payment->payment_date?->format('M j, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $payment->confirmed ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 'bg-amber-50 text-amber-700 ring-amber-600/20' }}">
                                {{ $payment->confirmed ? 'Confirmed' : 'Unconfirmed' }}
                            </span>
                            <a href="{{ route('suppliers.payments.edit', [$supplier, $payment]) }}" class="text-sm text-slate-400 hover:text-slate-600">Edit</a>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-8 text-center text-sm text-slate-500">No payments yet.</p>
                @endforelse
            </div>

            {{-- Applications --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">Applications <span class="text-slate-400 font-normal">({{ $supplier->applications->count() }})</span></h3>
                    <a href="{{ route('suppliers.applications.create', $supplier) }}" class="text-sm text-indigo-600 hover:underline">+ New Application</a>
                </div>
                @forelse ($supplier->applications as $application)
                    <div class="flex items-center justify-between px-6 py-3 border-b border-slate-50 last:border-b-0">
                        <span class="text-sm font-medium text-slate-800">Application #{{ $application->id }}</span>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-600/20">
                                {{ ucfirst($application->status) }}
                            </span>
                            <a href="{{ route('suppliers.applications.show', [$supplier, $application]) }}" class="text-sm text-slate-400 hover:text-slate-600">View</a>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-8 text-center text-sm text-slate-500">No applications yet.</p>
                @endforelse
            </div>

            {{-- Ledger --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">Ledger <span class="text-slate-400 font-normal">({{ $supplier->ledgerEntries->count() }})</span></h3>
                    <a href="{{ route('suppliers.ledger.create', $supplier) }}" class="text-sm text-indigo-600 hover:underline">+ Add Entry</a>
                </div>
                @forelse ($supplier->ledgerEntries as $entry)
                    <div class="flex items-center justify-between px-6 py-3 border-b border-slate-50 last:border-b-0">
                        <div>
                            <span class="text-sm font-medium text-slate-800">${{ number_format($entry->amount, 2) }}</span>
                            <span class="text-xs text-slate-400 ml-2">{{ $entry->direction === 'we_owe_you' ? 'We Owe You' : 'You Owe Us' }} — {{ $entry->description }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $entry->agreed ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 'bg-amber-50 text-amber-700 ring-amber-600/20' }}">
                                {{ $entry->agreed ? 'Agreed' : 'Unagreed' }}
                            </span>
                            <a href="{{ route('suppliers.ledger.edit', [$supplier, $entry]) }}" class="text-sm text-slate-400 hover:text-slate-600">Edit</a>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-8 text-center text-sm text-slate-500">No ledger entries yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>