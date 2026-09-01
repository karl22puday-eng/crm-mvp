<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Clearline') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased h-screen overflow-hidden bg-[#0B0B14]" x-data="{ mobileOpen: false }">

    <div class="h-screen flex">

        {{-- Slim icon-only rail --}}
        <aside class="w-[72px] shrink-0 flex flex-col items-center py-5 bg-[#0B0B14] border-r border-white/5">
            <a href="{{ route('dashboard') }}" class="mb-8">
                <svg width="34" height="34" viewBox="0 0 28 28" fill="none">
                    <rect width="28" height="28" rx="8" fill="url(#g)"/>
                    <path d="M8 18.5L13 9L15 14L20 9.5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <defs><linearGradient id="g" x1="0" y1="0" x2="28" y2="28"><stop stop-color="#818CF8"/><stop offset="1" stop-color="#F472B6"/></linearGradient></defs>
                </svg>
            </a>

            <nav class="flex-1 flex flex-col items-center gap-2">
                @php
                    $items = auth()->user()->role === 'staff'
                        ? [['dashboard','Dashboard','M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3'],
                           ['suppliers.index','Suppliers','M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4']]
                        : [['dashboard','Dashboard','M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3'],
                           ['portal.spots','My Spots','M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                           ['portal.cards','My Cards','M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                           ['portal.payments','My Payments','M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1'],
                           ['portal.ledger','Our Account','M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7'],
                          ];
                @endphp
                @foreach ($items as [$route, $label, $icon])
                    @php $active = request()->routeIs($route.'*') || request()->routeIs($route); @endphp
                    <a href="{{ route($route) }}"
                       x-data="{ tip: false }" @mouseenter="tip = true" @mouseleave="tip = false"
                       class="relative w-11 h-11 rounded-xl flex items-center justify-center transition
                              {{ $active ? 'bg-white text-[#0B0B14]' : 'text-slate-500 hover:text-white hover:bg-white/5' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
                        </svg>
                        <div x-show="tip" x-cloak x-transition.opacity
                             class="absolute left-14 top-1/2 -translate-y-1/2 z-50 whitespace-nowrap bg-white text-[#0B0B14] text-xs font-medium px-2.5 py-1.5 rounded-lg shadow-xl">
                            {{ $label }}
                        </div>
                    </a>
                @endforeach
            </nav>

            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-pink-400 flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </aside>

        {{-- Content: dark gradient canvas --}}
        <div class="flex-1 flex flex-col min-w-0 bg-gradient-to-br from-[#0B0B14] via-[#13131F] to-[#1A1A2E] rounded-l-[28px] overflow-hidden">
            <header class="h-20 flex items-center px-8 shrink-0">
                @isset($header){{ $header }}@endisset
            </header>
            <main class="flex-1 overflow-y-auto px-8 pb-10">
                <div class="max-w-[1400px] mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>