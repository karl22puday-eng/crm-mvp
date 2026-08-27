<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Suppliers</h2>
            <a href="{{ route('suppliers.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                + New Supplier
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posting Rate</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('suppliers.show', $supplier) }}" class="text-indigo-600 hover:underline">
                                        {{ $supplier->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $supplier->email ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ ucfirst(str_replace('_', ' ', $supplier->tier)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $supplier->posting_rate }}%</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('suppliers.edit', $supplier) }}" class="text-sm text-gray-500 hover:underline">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No suppliers yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</x-app-layout>