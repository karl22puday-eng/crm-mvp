<x-app-layout>
    <x-slot name="header">
        <h2 class="text-[28px] font-semibold text-white tracking-tight">Application — {{ $supplier->name }}</h2>
    </x-slot>

    @if (session('status'))
        <div class="mb-5 flex items-center gap-2 px-4 py-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-sm rounded-xl">
            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <div class="bg-black/30 backdrop-blur-sm border border-white/10 rounded-3xl p-6 h-fit">
            <h3 class="font-semibold text-white mb-4">Financial Profile</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Annual Revenue</p>
                    <p class="text-xl font-bold text-white tabular">{{ $application->financialProfile?->annual_revenue ? '$'.number_format($application->financialProfile->annual_revenue) : '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Annual Net Profit</p>
                    <p class="text-xl font-bold text-white tabular">{{ $application->financialProfile?->annual_net_profit ? '$'.number_format($application->financialProfile->annual_net_profit) : '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Avg Monthly Deposits</p>
                    <p class="text-xl font-bold text-white tabular">{{ $application->financialProfile?->avg_monthly_deposits ? '$'.number_format($application->financialProfile->avg_monthly_deposits) : '—' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('suppliers.applications.calculate', [$supplier, $application]) }}" class="mt-6 pt-6 border-t border-white/10">
                @csrf
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-br from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-900/30 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    {{ $application->approvalCalculations->count() ? 'Recalculate' : 'Run Calculation' }}
                </button>
            </form>
        </div>

        <div class="lg:col-span-2">
            @if ($application->approvalCalculations->count())
                @php
                    $lanes = $application->approvalCalculations->groupBy('lane');
                    $laneMeta = [
                        'fintech_term_loan' => ['label' => 'Fintech Term Loan', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                        'fintech_loc' => ['label' => 'Fintech Line of Credit', 'icon' => 'M17 9V7a4 4 0 00-8 0v2m-2 0h12a2 2 0 012 2v7a2 2 0 01-2 2H7a2 2 0 01-2-2v-7a2 2 0 012-2z'],
                        'bank_term_loan' => ['label' => 'Bank Term Loan', 'icon' => 'M3 21h18M5 21V7l8-4v18M19 21V11l-6-4'],
                        'bank_loc' => ['label' => 'Bank Line of Credit', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1'],
                    ];
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($lanes as $lane => $calcs)
                        @php
                            $lowest = $calcs->sortBy('amount')->first();
                            $meta = $laneMeta[$lane] ?? ['label' => $lane, 'icon' => ''];
                        @endphp
                        <div class="bg-black/30 backdrop-blur-sm border border-white/10 rounded-3xl p-5">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="h-8 w-8 rounded-lg bg-indigo-500/15 text-indigo-300 flex items-center justify-center shrink-0">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $meta['icon'] }}" /></svg>
                                </div>
                                <p class="text-sm font-medium text-slate-400">{{ $meta['label'] }}</p>
                            </div>
                            <p class="text-3xl font-bold text-white tabular mb-2">${{ number_format($lowest->amount) }}</p>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-white/5 text-slate-400">
                                    Governed by {{ ucfirst(str_replace('_', ' ', $lowest->source_metric)) }}
                                </span>
                                @if ($lowest->flag)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-500/15 text-amber-300 ring-1 ring-inset ring-amber-500/30">
                                        {{ $lowest->flag }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="border border-dashed border-white/20 rounded-3xl p-12 text-center">
                    <svg class="mx-auto h-10 w-10 text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    <p class="text-sm text-slate-400">No calculation run yet.</p>
                    <p class="text-xs text-slate-600 mt-1">Click "Run Calculation" to size the approval.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
