<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Dashboard</h2>
    </x-slot>

    @php
        $totalSuppliers = \App\Models\Supplier::count();
        $totalCards = \App\Models\Card::count();
        $totalApplications = \App\Models\CrmApplication::count();
        $pendingSpots = \App\Models\AuAdd::where('minimum_met', false)->count();
        $recentSuppliers = \App\Models\Supplier::latest()->take(5)->get();
    @endphp

    {{-- Hero --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 p-8 mb-6">
        <div class="absolute -right-8 -top-8 h-40 w-40 rounded-full bg-indigo-500/10"></div>
        <div class="relative">
            <p class="text-slate-400 text-sm font-medium mb-1">Welcome back</p>
            <h1 class="text-2xl font-semibold text-white">{{ auth()->user()->name }}</h1>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-1">Suppliers</p>
            <p class="text-2xl font-semibold text-slate-800">{{ $totalSuppliers }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-1">Cards</p>
            <p class="text-2xl font-semibold text-slate-800">{{ $totalCards }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-1">Applications</p>
            <p class="text-2xl font-semibold text-slate-800">{{ $totalApplications }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-1">Spots needing check</p>
            <p class="text-2xl font-semibold {{ $pendingSpots > 0 ? 'text-amber-600' : 'text-slate-800' }}">{{ $pendingSpots }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Recent suppliers --}}
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-semibold text-slate-800">Recent suppliers</h3>
                <a href="{{ route('suppliers.index') }}" class="text-sm text-indigo-600 hover:underline">View all →</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($recentSuppliers as $supplier)
                    <a href="{{ route('suppliers.show', $supplier) }}" class="flex items-center gap-3 px-6 py-3 hover:bg-slate-50/70 transition">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white text-xs font-semibold shrink-0">
                            {{ strtoupper(substr($supplier->name, 0, 2)) }}
                        </div>
                        <span class="text-sm font-medium text-slate-800">{{ $supplier->name }}</span>
                        <span class="ml-auto text-xs text-slate-400">{{ $supplier->created_at->diffForHumans() }}</span>
                    </a>
                @empty
                    <p class="px-6 py-8 text-center text-sm text-slate-500">No suppliers yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-4">Quick actions</h3>
            <div class="space-y-2">
                <a href="{{ route('suppliers.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 transition group">
                    <div class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </div>
                    <span class="text-sm font-medium text-slate-700 group-hover:text-indigo-600">New Supplier</span>
                </a>
                <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 transition group">
                    <div class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" /></svg>
                    </div>
                    <span class="text-sm font-medium text-slate-700 group-hover:text-indigo-600">Browse Suppliers</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>