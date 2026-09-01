<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-0.5">{{ now()->format('l, F j') }}</p>
            <h2 class="text-[28px] font-semibold text-white tracking-tight">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}@if($supplier), {{ explode(' ', $supplier->name)[0] }}@endif
            </h2>
        </div>
    </x-slot>

    @if (! $supplier)
        <div class="bg-white rounded-3xl shadow-xl p-8 text-center">
            <p class="text-slate-500">No supplier record is linked to your account yet. Contact us to get set up.</p>
        </div>
    @else
        <div class="grid grid-cols-4 auto-rows-[136px] grid-flow-row-dense gap-4">

            {{-- HERO: jobs summary + actual pending items --}}
            <div class="col-span-2 row-span-2 bg-black/40 border border-white/10 rounded-3xl p-6 flex flex-col relative overflow-hidden backdrop-blur-sm">
                <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-indigo-500/20 blur-3xl"></div>

                <div class="relative shrink-0">
                    <p class="text-slate-400 text-sm mb-1">Waiting on you today</p>
                    <p class="text-white text-5xl font-bold tracking-tight tabular">${{ number_format($money['due_today'], 0) }}</p>
                </div>

                {{-- Actual pending items list --}}
                <div class="relative flex-1 overflow-y-auto mt-4 space-y-1.5 pr-1">
                    @foreach ($taskItems['spots']->take(3) as $spot)
                        <div class="flex items-center gap-2 text-sm">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-400 shrink-0"></span>
                            <span class="text-slate-300 truncate">Check bureaus — {{ $spot->client_name }}</span>
                        </div>
                    @endforeach
                    @foreach ($taskItems['cards']->take(3) as $card)
                        <div class="flex items-center gap-2 text-sm">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-400 shrink-0"></span>
                            <span class="text-slate-300 truncate">Confirm still open — {{ $card->issuer_name }} ({{ $card->card_code }})</span>
                        </div>
                    @endforeach
                    @foreach ($taskItems['payments']->take(3) as $payment)
                        <div class="flex items-center gap-2 text-sm">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                            <span class="text-slate-300 truncate">Confirm payment — ${{ number_format($payment->amount, 2) }}</span>
                        </div>
                    @endforeach

                    @if ($tasks['total_jobs'] === 0)
                        <div class="flex items-center gap-2 text-sm">
                            <svg class="h-4 w-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            <span class="text-slate-300">You're all caught up.</span>
                        </div>
                    @endif
                </div>

                <div class="relative shrink-0 mt-3">
                    <div class="flex items-center justify-between mb-1.5">
                        <p class="text-slate-400 text-xs">{{ $tasks['total_jobs'] }} job{{ $tasks['total_jobs'] === 1 ? '' : 's' }} left to clear</p>
                        @if ($tasks['total_jobs'] > 0)
                            <a href="{{ route('portal.spots') }}" class="text-xs text-indigo-300 hover:text-indigo-200 font-medium">Go clear them →</a>
                        @endif
                    </div>
                    @php
                        $donePct = $tasks['total_jobs'] === 0 ? 100 : max(0, 100 - ($tasks['total_jobs'] / 3) * 100);
                    @endphp
                    <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-400 to-violet-400 rounded-full" style="width: {{ $donePct }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Money stat tiles --}}
            <div class="col-span-1 bg-white rounded-3xl p-5 flex flex-col justify-between">
                <div class="h-9 w-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" /></svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-slate-900 tabular tracking-tight">${{ number_format($money['held'], 0) }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">Held</p>
                </div>
            </div>

            <div class="col-span-1 bg-white rounded-3xl p-5 flex flex-col justify-between">
                <div class="h-9 w-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-slate-900 tabular tracking-tight">${{ number_format($money['paid_so_far'], 0) }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">Paid so far</p>
                </div>
            </div>

            <div class="col-span-1 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl p-5 flex flex-col justify-between text-white">
                <div class="h-9 w-9 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                </div>
                <div>
                    <p class="text-3xl font-bold tabular tracking-tight">${{ number_format($money['earned'], 0) }}</p>
                    <p class="text-xs text-white/80 mt-0.5">Earned so far</p>
                </div>
            </div>

            {{-- Posting rate ring --}}
            <div class="col-span-1 bg-white rounded-3xl p-5 flex flex-col items-center justify-center">
                @php
                    $pct = min(max($money['posting_rate'], 0), 100);
                    $circumference = 2 * 3.14159 * 30;
                    $offset = $circumference - ($pct / 100) * $circumference;
                @endphp
                <div class="relative h-16 w-16">
                    <svg class="h-16 w-16 -rotate-90" viewBox="0 0 72 72">
                        <circle cx="36" cy="36" r="30" fill="none" stroke="#f1f5f9" stroke-width="7"/>
                        <circle cx="36" cy="36" r="30" fill="none" stroke="url(#ring2)" stroke-width="7" stroke-linecap="round"
                                stroke-dasharray="{{ $circumference }}" style="stroke-dashoffset: {{ $offset }}"/>
                        <defs><linearGradient id="ring2" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#6366f1"/><stop offset="1" stop-color="#a855f7"/></linearGradient></defs>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-sm font-bold text-slate-800 tabular">{{ $pct }}%</span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-2">Posting rate</p>
            </div>

            {{-- Jobs stepper --}}
            <div class="col-span-2 row-span-1 bg-white rounded-3xl p-5">
                @php
                    $jobs = [
                        ['label' => 'Check bureaus', 'count' => $tasks['spots_needing_check']],
                        ['label' => 'Confirm cards open', 'count' => $tasks['cards_needing_confirmation']],
                        ['label' => 'Confirm payments', 'count' => $tasks['payments_needing_confirmation']],
                    ];
                @endphp
                <div class="flex items-center justify-between h-full">
                    @foreach ($jobs as $i => $job)
                        @php $done = $job['count'] === 0; @endphp
                        <div class="flex flex-col items-center text-center flex-1">
                            <div class="h-9 w-9 rounded-full flex items-center justify-center text-xs font-bold mb-2
                                {{ $done ? 'bg-emerald-500 text-white' : 'bg-amber-100 text-amber-700' }}">
                                @if ($done)
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                @else
                                    {{ $job['count'] }}
                                @endif
                            </div>
                            <p class="text-xs text-slate-600 font-medium leading-tight">{{ $job['label'] }}</p>
                        </div>
                        @if (!$loop->last)
                            <div class="h-px flex-1 bg-slate-100 mb-6 mx-1"></div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Quick links --}}
            <div class="col-span-2 row-span-1 bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl p-5 flex flex-col justify-center gap-1">
                @foreach ([['portal.spots','My Spots'],['portal.cards','My Cards'],['portal.payments','My Payments'],['portal.ledger','Our Account']] as [$route, $label])
                    <a href="{{ route($route) }}" class="flex items-center justify-between text-sm text-slate-300 hover:text-white py-1 group transition">
                        {{ $label }}
                        <svg class="h-3.5 w-3.5 text-slate-500 group-hover:text-white group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</x-app-layout>
