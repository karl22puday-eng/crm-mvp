<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Suppliers</h2>
    </x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm text-slate-500">{{ $suppliers->total() }} total supplier{{ $suppliers->total() === 1 ? '' : 's' }}</p>
        </div>
        <a href="{{ route('suppliers.create') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg shadow-sm transition">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Supplier
        </a>
    </div>

    @if (session('status'))
        <div class="mb-6 flex items-center gap-2 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50/60">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Supplier</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Tier</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Posting Rate</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($suppliers as $supplier)
                    @php
                        $tierStyles = [
                            'preferred' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                            'standard' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                            'under_review' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                        ];
                        $tierStyle = $tierStyles[$supplier->tier] ?? $tierStyles['under_review'];
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="px-6 py-4">
                            <a href="{{ route('suppliers.show', $supplier) }}" class="flex items-center gap-3 group">
                                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white text-xs font-semibold shrink-0">
                                    {{ strtoupper(substr($supplier->name, 0, 2)) }}
                                </div>
                                <span class="font-medium text-slate-800 group-hover:text-indigo-600 transition">{{ $supplier->name }}</span>
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $supplier->email ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $tierStyle }}">
                                {{ ucfirst(str_replace('_', ' ', $supplier->tier)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $supplier->posting_rate }}%</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="text-sm text-slate-400 hover:text-indigo-600 transition">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <svg class="mx-auto h-10 w-10 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                            </svg>
                            <p class="text-sm text-slate-500">No suppliers yet.</p>
                            <a href="{{ route('suppliers.create') }}" class="inline-block mt-3 text-sm text-indigo-600 font-medium hover:underline">
                                Add your first supplier →
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $suppliers->links() }}
    </div>
</x-app-layout>