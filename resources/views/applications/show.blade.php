<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Application — {{ $supplier->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-green-100 text-green-800 rounded-md">{{ session('status') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-2">Financial Profile</h3>
                <dl class="grid grid-cols-3 gap-4 text-sm">
                    <div><dt class="text-gray-500">Revenue</dt><dd>{{ $application->financialProfile?->annual_revenue ? '$'.number_format($application->financialProfile->annual_revenue) : '—' }}</dd></div>
                    <div><dt class="text-gray-500">Net Profit</dt><dd>{{ $application->financialProfile?->annual_net_profit ? '$'.number_format($application->financialProfile->annual_net_profit) : '—' }}</dd></div>
                    <div><dt class="text-gray-500">Monthly Deposits</dt><dd>{{ $application->financialProfile?->avg_monthly_deposits ? '$'.number_format($application->financialProfile->avg_monthly_deposits) : '—' }}</dd></div>
                </dl>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold">Approval Calculation</h3>
                    <form method="POST" action="{{ route('suppliers.applications.calculate', [$supplier, $application]) }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                            {{ $application->approvalCalculations->count() ? 'Recalculate' : 'Run Calculation' }}
                        </button>
                    </form>
                </div>

                @if ($application->approvalCalculations->count())
                    @php
                        $lanes = $application->approvalCalculations->groupBy('lane');
                        $laneLabels = [
                            'fintech_term_loan' => 'Fintech Term Loan',
                            'fintech_loc' => 'Fintech Line of Credit',
                            'bank_term_loan' => 'Bank Term Loan',
                            'bank_loc' => 'Bank Line of Credit',
                        ];
                    @endphp

                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="py-2">Lane</th>
                                <th class="py-2">Amount</th>
                                <th class="py-2">Governed By</th>
                                <th class="py-2">Flag</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lanes as $lane => $calcs)
                                @php $lowest = $calcs->sortBy('amount')->first(); @endphp
                                <tr class="border-b">
                                    <td class="py-2 font-medium">{{ $laneLabels[$lane] ?? $lane }}</td>
                                    <td class="py-2">${{ number_format($lowest->amount) }}</td>
                                    <td class="py-2 text-gray-500">{{ ucfirst(str_replace('_', ' ', $lowest->source_metric)) }}</td>
                                    <td class="py-2 text-gray-500">{{ $lowest->flag ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-sm text-gray-500">No calculation run yet. Click "Run Calculation" above.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>