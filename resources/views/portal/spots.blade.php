<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Spots</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @php
                $held = $spots->where('minimum_met', false)->count();
                $heldAmount = $spots->where('minimum_met', false)->sum('rate');
            @endphp

            <div class="bg-indigo-600 text-white rounded-lg p-4 mb-6">
                ${{ number_format($heldAmount, 0) }} is held on {{ $held }} spot(s) waiting on your check.
                Work down the rows below.
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm whitespace-nowrap">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Card</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Added</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Experian?</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Equifax?</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">TransUnion?</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date I Checked</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Where I Saw It</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">What Happens To My Money</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($spots as $i => $spot)
                            @php
                                $message = $spot->minimum_met
                                    ? ($spot->paid ? 'Paid to you.' : 'Cleared the minimum — waiting to be sent to you.')
                                    : 'Held. Check all three bureaus and date it to release this.';
                            @endphp
                            <tr>
                                <td class="px-3 py-3 text-gray-400">{{ $i + 1 }}</td>
                                <td class="px-3 py-3 font-medium">{{ $spot->client_name }}</td>
                                <td class="px-3 py-3 text-gray-500">{{ $spot->card_label }}</td>
                                <td class="px-3 py-3">{{ $spot->date_added?->format('M j, Y') ?? '—' }}</td>
                                <td class="px-3 py-3">{{ is_null($spot->experian_showing) ? '—' : ($spot->experian_showing ? 'Y' : 'N') }}</td>
                                <td class="px-3 py-3">{{ is_null($spot->equifax_showing) ? '—' : ($spot->equifax_showing ? 'Y' : 'N') }}</td>
                                <td class="px-3 py-3">{{ is_null($spot->transunion_showing) ? '—' : ($spot->transunion_showing ? 'Y' : 'N') }}</td>
                                <td class="px-3 py-3">{{ $spot->date_checked?->format('M j, Y') ?? '—' }}</td>
                                <td class="px-3 py-3 text-gray-500">{{ $spot->source ?? '—' }}</td>
                                <td class="px-3 py-3 text-gray-500">{{ $message }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-3 py-4 text-center text-gray-500">No spots yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>