<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">My Spots</h2>
    </x-slot>

    @php
        $held = $spots->where('minimum_met', false)->count();
        $heldAmount = $spots->where('minimum_met', false)->sum('rate');
    @endphp

    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 p-6 mb-6">
        <p class="text-white font-medium">
            ${{ number_format($heldAmount, 0) }} is held on {{ $held }} spot(s) waiting on your check.
        </p>
        <p class="text-indigo-100 text-sm mt-1">Work down the rows below to release it.</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Client</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Card</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Date Added</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">EX</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">EQ</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">TU</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Date Checked</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Where Seen</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">What Happens</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($spots as $spot)
                        @php
                            $message = $spot->minimum_met
                                ? ($spot->paid ? 'Paid to you.' : 'Cleared — waiting to be sent.')
                                : 'Held. Check all three bureaus to release this.';
                            $dot = fn($v) => is_null($v) ? '<span class="inline-block h-2.5 w-2.5 rounded-full bg-slate-200"></span>' : ($v ? '<span class="inline-block h-2.5 w-2.5 rounded-full bg-emerald-500"></span>' : '<span class="inline-block h-2.5 w-2.5 rounded-full bg-rose-400"></span>');
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $spot->client_name }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $spot->card_label }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $spot->date_added?->format('M j, Y') ?? '—' }}</td>
                            <td class="px-4 py-3 text-center">{!! $dot($spot->experian_showing) !!}</td>
                            <td class="px-4 py-3 text-center">{!! $dot($spot->equifax_showing) !!}</td>
                            <td class="px-4 py-3 text-center">{!! $dot($spot->transunion_showing) !!}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $spot->date_checked?->format('M j, Y') ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $spot->source ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $spot->minimum_met ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 'bg-amber-50 text-amber-700 ring-amber-600/20' }}">
                                    {{ $message }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="px-4 py-10 text-center text-slate-500">No spots yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>