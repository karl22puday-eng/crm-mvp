<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Payments</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @php
                $needConfirming = $payments->whereNull('confirmed')->count();
            @endphp

            <div class="bg-indigo-600 text-white rounded-lg p-4 mb-6">
                We have sent you {{ $payments->count() }} payment(s). {{ $needConfirming }} still need a Yes or No from you.
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm whitespace-nowrap">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Date</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Covers</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Got It?</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date I Got It</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount Looks Wrong?</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">My Comment</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">What To Do Next</th>
                            
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($payments as $i => $payment)
                            @php
                                $nextStep = is_null($payment->confirmed)
                                    ? 'Tell us this payment arrived.'
                                    : ($payment->confirmed ? 'Nothing needed — confirmed.' : 'Flagged as not received — we will follow up.');
                            @endphp
                            <tr>
                                <td class="px-3 py-3 text-gray-400">{{ $i + 1 }}</td>
                                <td class="px-3 py-3">{{ $payment->payment_date?->format('M j, Y') ?? '—' }}</td>
                                <td class="px-3 py-3 font-medium">${{ number_format($payment->amount, 2) }}</td>
                                <td class="px-3 py-3 text-gray-500">{{ $payment->covers ?? '—' }}</td>
                                <td class="px-3 py-3">
                                    @if (is_null($payment->confirmed))
                                        <span class="text-amber-600">Needs answer</span>
                                    @else
                                        <span class="{{ $payment->confirmed ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $payment->confirmed ? 'Y' : 'N' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-3">{{ $payment->confirmed_date?->format('M j, Y') ?? '—' }}</td>
                                <td class="px-3 py-3">{{ $payment->amount_disputed ? 'Y' : 'N' }}</td>
                                <td class="px-3 py-3 text-gray-500">{{ $payment->comment ?? '—' }}</td>
                                <td class="px-3 py-3 text-gray-500">{{ $nextStep }}</td>
                                
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-3 py-4 text-center text-gray-500">No payments yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>