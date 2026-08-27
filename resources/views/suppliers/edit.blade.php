<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit {{ $supplier->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
                    @csrf
                    @method('PUT')
                    @include('suppliers._form')

                    <div class="mt-6 flex justify-between">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                            Save Changes
                        </button>
                        <a href="{{ route('suppliers.show', $supplier) }}" class="px-4 py-2 text-gray-500 text-sm">
                            Cancel
                        </a>
                    </div>
                </form>

                <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}"
                      onsubmit="return confirm('Delete this supplier? This cannot be undone.')" class="mt-4 pt-4 border-t">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-600">Delete Supplier</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>