@php
    $linkBase = 'flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition';
    $linkActive = 'bg-indigo-500/10 text-white';
    $linkInactive = 'text-slate-400 hover:text-white hover:bg-slate-800';
@endphp

<div>
    <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">General</p>
    <a href="{{ route('dashboard') }}"
       class="{{ $linkBase }} {{ request()->routeIs('dashboard') ? $linkActive : $linkInactive }}">
        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        Dashboard
    </a>
</div>

@if (auth()->user()->role === 'staff')
    <div>
        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Workspace</p>
        <div class="space-y-1">
            <a href="{{ route('suppliers.index') }}"
               class="{{ $linkBase }} {{ request()->routeIs('suppliers.*') ? $linkActive : $linkInactive }}">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                </svg>
                Suppliers
            </a>
        </div>
    </div>
@else
    <div>
        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">My Account</p>
        <div class="space-y-1">
            <a href="{{ route('portal.spots') }}"
               class="{{ $linkBase }} {{ request()->routeIs('portal.spots') ? $linkActive : $linkInactive }}">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                My Spots
            </a>
            <a href="{{ route('portal.cards') }}"
               class="{{ $linkBase }} {{ request()->routeIs('portal.cards') ? $linkActive : $linkInactive }}">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                My Cards
            </a>
            <a href="{{ route('portal.payments') }}"
               class="{{ $linkBase }} {{ request()->routeIs('portal.payments') ? $linkActive : $linkInactive }}">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                </svg>
                My Payments
            </a>
            <a href="{{ route('portal.ledger') }}"
               class="{{ $linkBase }} {{ request()->routeIs('portal.ledger') ? $linkActive : $linkInactive }}">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                </svg>
                Our Account
            </a>
        </div>
    </div>
@endif