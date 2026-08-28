<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Application — {{ $supplier->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-sm text-gray-500 mb-4">Enter whichever financial metrics you have. The engine will size an approval using every metric provided.</p>

                <form method="POST" action="{{ route('suppliers.applications.store', $supplier) }}">
                    @csrf

                    <div>
                        <x-input-label for="annual_revenue" value="Annual Revenue" />
                        <x-text-input id="annual_revenue" name="annual_revenue" type="number" step="0.01" class="block mt-1 w-full"
                                      value="{{ old('annual_revenue') }}" />
                        <x-input-error :messages="$errors->get('annual_revenue')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="annual_net_profit" value="Annual Net Profit" />
                        <x-text-input id="annual_net_profit" name="annual_net_profit" type="number" step="0.01" class="block mt-1 w-full"
                                      value="{{ old('annual_net_profit') }}" />
                        <x-input-error :messages="$errors->get('annual_net_profit')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="avg_monthly_deposits" value="Average Monthly Deposits" />
                        <x-text-input id="avg_monthly_deposits" name="avg_monthly_deposits" type="number" step="0.01" class="block mt-1 w-full"
                                      value="{{ old('avg_monthly_deposits') }}" />
                        <x-input-error :messages="$errors->get('avg_monthly_deposits')" class="mt-2" />
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                            Create Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>