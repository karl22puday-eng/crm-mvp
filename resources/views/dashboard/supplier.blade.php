<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($supplier)
                    <p>Hello {{ $supplier->name }}. You are owed $0 right now — your cards, spots, and payments will appear here.</p>
                @else
                    <p>No supplier record found for your account.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>