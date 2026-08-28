<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $supplier->name }}</h2>
            <a href="{{ route('suppliers.edit', $supplier) }}" class="text-sm text-indigo-600 hover:underline">Edit</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-green-100 text-green-800 rounded-md">{{ session('status') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-2 gap-4">
                    <div><dt class="text-sm text-gray-500">Email</dt><dd>{{ $supplier->email ?? '—' }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Phone</dt><dd>{{ $supplier->phone ?? '—' }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Tier</dt><dd>{{ ucfirst(str_replace('_', ' ', $supplier->tier)) }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Posting Rate</dt><dd>{{ $supplier->posting_rate }}%</dd></div>
                </dl>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold">Cards ({{ $supplier->cards->count() }})</h3>
                    <a href="{{ route('suppliers.cards.create', $supplier) }}" class="text-sm text-indigo-600 hover:underline">
                        + Add Card
                    </a>
                </div>

                @forelse ($supplier->cards as $card)
                    <div class="py-3 border-t first:border-t-0">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="font-medium">{{ $card->issuer_name }}</span>
                                <span class="text-gray-500 text-sm">— {{ $card->card_code }} — {{ ucfirst($card->status) }}</span>
                            </div>
                            <div class="space-x-3">
                                <a href="{{ route('suppliers.cards.auAdds.create', [$supplier, $card]) }}" class="text-sm text-indigo-600 hover:underline">+ Add Spot</a>
                                <a href="{{ route('suppliers.cards.edit', [$supplier, $card]) }}" class="text-sm text-gray-500 hover:underline">Edit</a>
                            </div>
                        </div>

                        @if ($card->auAdds->count())
                            <div class="mt-2 pl-4 space-y-1">
                                @foreach ($card->auAdds as $auAdd)
                                    <div class="flex justify-between items-center text-sm">
                                        <span>{{ $auAdd->client_name }} — {{ ucfirst($auAdd->payout_status) }}</span>
                                        <a href="{{ route('suppliers.cards.auAdds.edit', [$supplier, $card, $auAdd]) }}" class="text-gray-500 hover:underline">Edit</a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No cards yet.</p>
                @endforelse
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold">Payments ({{ $supplier->payments->count() }})</h3>
                    <a href="{{ route('suppliers.payments.create', $supplier) }}" class="text-sm text-indigo-600 hover:underline">
                        + Record Payment
                    </a>
                </div>

                @forelse ($supplier->payments as $payment)
                    <div class="flex justify-between items-center py-2 border-t first:border-t-0">
                        <div>
                            <span class="font-medium">${{ number_format($payment->amount, 2) }}</span>
                            <span class="text-gray-500 text-sm">— {{ $payment->payment_date?->format('M j, Y') }} — {{ $payment->confirmed ? 'Confirmed' : 'Unconfirmed' }}</span>
                        </div>
                        <a href="{{ route('suppliers.payments.edit', [$supplier, $payment]) }}" class="text-sm text-gray-500 hover:underline">Edit</a>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No payments yet.</p>
                @endforelse
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-2">Applications ({{ $supplier->applications->count() }})</h3>
                <p class="text-sm text-gray-500">Application list coming in the next step.</p>
            </div>
        </div>
    </div>
</x-app-layout>