<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Dashboard</h2>
    </x-slot>

    @if (! $supplier)
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-8 text-center">
            <p class="text-slate-500">No supplier record is linked to your account yet. Contact us to get set up.</p>
        </div>
    @else
        {{-- Hero banner --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 p-8 mb-6">
            <div class="absolute -right-8 -top-8 h-40 w-40 rounded-full bg-white/10"></div>
            <div class="absolute -right-2 top-16 h-20 w-20 rounded-full bg-white/10"></div>
            <div class="relative">
                <p class="text-indigo-100 text-sm font-medium mb-1">Welcome back</p>
                <h1 class="text-2xl font-semibold text-white mb-3">Hello {{ $supplier->name }}</h1>
                <p class="text-indigo-50">
                    You have <span class="font-semibold text-white">{{ $tasks['total_jobs'] }} job{{ $tasks['total_jobs'] === 1 ? '' : 's' }}</span> to do
                    and <span class="font-semibold text-white">${{ number_format($money['due_today'], 0) }}</span> is waiting on you.
                </p>
            </div>
        </div>

        {{-- Money stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-1">Due today</p>
                <p class="text-2xl font-semibold text-emerald-600">${{ number_format($money['due_today'], 0) }}</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-1">Held</p>
                <p class="text-2xl font-semibold text-amber-600">${{ number_format($money['held'], 0) }}</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-1">Paid so far</p>
                <p class="text-2xl font-semibold text-slate-800">${{ number_format($money['paid_so_far'], 0) }}</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-1">Earned so far</p>
                <p class="text-2xl font-semibold text-slate-800">${{ number_format($money['earned'], 0) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Jobs to do --}}
            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-800">Your three jobs, in order</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @php
                        $jobs = [
                            ['label' => 'Check bureaus on your spots', 'sub' => 'The only thing that releases your money.', 'count' => $tasks['spots_needing_check'], 'unit' => 'spot'],
                            ['label' => 'Confirm your cards are still open', 'sub' => 'A closed card drops every spot on it.', 'count' => $tasks['cards_needing_confirmation'], 'unit' => 'card'],
                            ['label' => 'Confirm payments when they land', 'sub' => 'An unconfirmed payment looks unpaid to both of us.', 'count' => $tasks['payments_needing_confirmation'], 'unit' => 'payment'],
                        ];
                    @endphp
                    @foreach ($jobs as $i => $job)
                        <div class="flex items-center gap-4 px-6 py-4">
                            <div class="h-8 w-8 rounded-full {{ $job['count'] > 0 ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center text-sm font-semibold shrink-0">
                                {{ $i + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800">{{ $job['label'] }}</p>
                                <p class="text-xs text-slate-500">{{ $job['sub'] }}</p>
                            </div>
                            <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                {{ $job['count'] > 0 ? 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20' : 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20' }}">
                                {{ $job['count'] }} {{ Str::plural($job['unit'], $job['count']) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Standing --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Your standing</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-500">Posting rate</span>
                            <span class="font-medium text-slate-800">{{ $money['posting_rate'] }}%</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $money['posting_rate'] }}%"></div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-sm text-slate-500">Tier</span>
                        @php
                            $tierBadge = match($money['tier']) {
                                'Preferred' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                'Standard' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                default => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $tierBadge }}">
                            {{ $money['tier'] }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                        <span class="text-sm text-slate-500">Side account</span>
                        <span class="font-medium text-slate-800">${{ number_format($money['side_account'], 0) }}</span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-100 space-y-2">
                    <a href="{{ route('portal.spots') }}" class="flex items-center justify-between text-sm text-slate-600 hover:text-indigo-600 py-1.5 transition">
                        My Spots <span>→</span>
                    </a>
                    <a href="{{ route('portal.cards') }}" class="flex items-center justify-between text-sm text-slate-600 hover:text-indigo-600 py-1.5 transition">
                        My Cards <span>→</span>
                    </a>
                    <a href="{{ route('portal.payments') }}" class="flex items-center justify-between text-sm text-slate-600 hover:text-indigo-600 py-1.5 transition">
                        My Payments <span>→</span>
                    </a>
                    <a href="{{ route('portal.ledger') }}" class="flex items-center justify-between text-sm text-slate-600 hover:text-indigo-600 py-1.5 transition">
                        Our Account <span>→</span>
                    </a>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>