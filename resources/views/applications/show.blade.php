<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Application — {{ $supplier->name }}</h2>
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

        {{-- Financial inputs --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 h-fit">
            <h3 class="font-semibold text-slate-800 mb-4">Financial Profile</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Annual Revenue</p>
                    <p class="text-xl font-semibold text-slate-800">
                        {{ $application->financialProfile?->annual_revenue ? '$'.number_format($application->financialProfile->annual_revenue) : '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Annual Net Profit</p>
                    <p class="text-xl font-semibold text-slate-800">
                        {{ $application->financialProfile?->annual_net_profit ? '$'.number_format($application->financialProfile->annual_net_profit) : '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Avg Monthly Deposits</p>
                    <p class="text-xl font-semibold text-slate-800">
                        {{ $application->financialProfile?->avg_monthly_deposits ? '$'.number_format($application->financialProfile->avg_monthly_deposits) : '—' }}
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('suppliers.applications.calculate', [$supplier, $application]) }}" class="mt-6 pt-6 border-t border-slate-100">
                @csrf
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg shadow-sm transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    {{ $application->approvalCalculations->count() ? 'Recalculate' : 'Run Calculation' }}
                </button>
            </form>
        </div>

        {{-- Results --}}
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
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon'] }}" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-slate-600">{{ $meta['label'] }}</p>
                            </div>
                            <p class="text-3xl font-semibold text-slate-800 mb-2">${{ number_format($lowest->amount) }}</p>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                    Governed by {{ ucfirst(str_replace('_', ' ', $lowest->source_metric)) }}
                                </span>
                                @if ($lowest->flag)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                        {{ $lowest->flag }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white border border-slate-200 border-dashed rounded-xl p-12 text-center">
                    <svg class="mx-auto h-10 w-10 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <p class="text-sm text-slate-500">No calculation run yet.</p>
                    <p class="text-xs text-slate-400 mt-1">Click "Run Calculation" to size the approval.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>