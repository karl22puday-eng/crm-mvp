<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-0.5">{{ now()->format('l, F j') }}</p>
            <h2 class="text-[28px] font-semibold text-white tracking-tight">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }}</h2>
        </div>
    </x-slot>

    @php
        $totalSuppliers = \App\Models\Supplier::count();
        $totalCards = \App\Models\Card::count();
        $totalApplications = \App\Models\CrmApplication::count();
        $pendingSpots = \App\Models\AuAdd::where('minimum_met', false)->count();
        $recentSuppliers = \App\Models\Supplier::latest()->take(4)->get();

        $days = collect(range(6, 0))->map(fn($d) => now()->subDays($d)->format('Y-m-d'));
        $counts = $days->map(fn($d) => \App\Models\Supplier::whereDate('created_at', $d)->count());
        $max = max($counts->max(), 1);
        $points = $counts->values()->map(function ($c, $i) use ($max, $counts) {
            $x = ($i / max($counts->count() - 1, 1)) * 280;
            $y = 70 - ($c / $max) * 60;
            return "$x,$y";
        })->implode(' ');
    @endphp

    <div class="grid grid-cols-4 auto-rows-[136px] grid-flow-row-dense gap-4">

        {{-- HERO --}}
        <div class="col-span-2 row-span-2 bg-black/40 border border-white/10 rounded-3xl p-6 flex flex-col justify-between relative overflow-hidden backdrop-blur-sm">
            <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-indigo-500/20 blur-3xl"></div>
            <div class="relative">
                <p class="text-slate-400 text-sm mb-1">Total suppliers</p>
                <p class="text-white text-6xl font-bold tracking-tight tabular">{{ $totalSuppliers }}</p>
            </div>
            <div class="relative">
                <svg viewBox="0 0 280 80" class="w-full h-16" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="sparkFill" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#818CF8" stop-opacity="0.35"/>
                            <stop offset="100%" stop-color="#818CF8" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <polyline points="{{ $points }}" fill="none" stroke="#818CF8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <polygon points="0,80 {{ $points }} 280,80" fill="url(#sparkFill)" stroke="none"/>
                </svg>
                <p class="text-slate-500 text-xs mt-1">Last 7 days</p>
            </div>
        </div>

        {{-- Stat tiles --}}
        <div class="col-span-1 bg-white rounded-3xl p-5 flex flex-col justify-between">
            <div class="h-9 w-9 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-900 tabular tracking-tight">{{ $totalCards }}</p>
                <p class="text-xs text-slate-400 mt-0.5">Cards</p>
            </div>
        </div>

        <div class="col-span-1 bg-white rounded-3xl p-5 flex flex-col justify-between">
            <div class="h-9 w-9 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-900 tabular tracking-tight">{{ $totalApplications }}</p>
                <p class="text-xs text-slate-400 mt-0.5">Applications</p>
            </div>
        </div>

        <div class="col-span-1 bg-gradient-to-br from-rose-500 to-orange-400 rounded-3xl p-5 flex flex-col justify-between text-white">
            <div class="h-9 w-9 rounded-xl bg-white/20 flex items-center justify-center">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
            </div>
            <div>
                <p class="text-3xl font-bold tabular tracking-tight">{{ $pendingSpots }}</p>
                <p class="text-xs text-white/80 mt-0.5">Need bureau check</p>
            </div>
        </div>

        <div class="col-span-1 bg-white rounded-3xl p-5 flex flex-col justify-center items-center text-center">
            <p class="text-3xl font-bold text-slate-900 tabular tracking-tight">
                {{ $totalSuppliers > 0 ? round($totalApplications / max($totalSuppliers,1) * 100) : 0 }}%
            </p>
            <p class="text-xs text-slate-400 mt-1">Suppliers with an application</p>
        </div>

        {{-- Recent suppliers --}}
        <div class="col-span-2 row-span-1 bg-white rounded-3xl p-5 flex flex-col">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-slate-800">Recent suppliers</p>
                <a href="{{ route('suppliers.index') }}" class="text-xs text-indigo-600 font-medium">View all →</a>
            </div>
            <div class="flex -space-x-2 mb-3">
                @forelse ($recentSuppliers as $s)
                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-indigo-400 to-pink-400 border-2 border-white flex items-center justify-center text-white text-xs font-bold" title="{{ $s->name }}">
                        {{ strtoupper(substr($s->name, 0, 2)) }}
                    </div>
                @empty
                    <p class="text-xs text-slate-400">No suppliers yet.</p>
                @endforelse
            </div>
            <p class="text-xs text-slate-400 mt-auto">{{ $recentSuppliers->count() }} onboarded recently</p>
        </div>

        {{-- CTA tile --}}
        <a href="{{ route('suppliers.create') }}" class="col-span-2 row-span-1 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl p-5 flex items-center justify-between text-white group hover:from-indigo-400 hover:to-purple-500 transition">
            <div>
                <p class="font-semibold">Add a new supplier</p>
                <p class="text-xs text-indigo-100 mt-0.5">Onboard a cardholder in seconds</p>
            </div>
            <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center group-hover:bg-white/30 transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
            </div>
        </a>
    </div>
</x-app-layout>
