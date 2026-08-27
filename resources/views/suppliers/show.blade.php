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
                <h3 class="font-semibold mb-2">Cards ({{ $supplier->cards->count() }})</h3>
                <p class="text-sm text-gray-500">Card list coming in the next step.</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-2">Applications ({{ $supplier->applications->count() }})</h3>
                <p class="text-sm text-gray-500">Application list coming in the next step.</p>
            </div>
        </div>
    </div>
</x-app-layout>