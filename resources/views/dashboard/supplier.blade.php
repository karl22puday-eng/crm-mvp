<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Your Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (! $supplier)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-500">No supplier record is linked to your account yet. Contact us to get set up.</p>
                </div>
            @else
                {{-- LIVE banner, mirrors START HERE row 2 --}}
                <div class="bg-indigo-600 text-white rounded-lg p-6">
                    <p class="text-lg">
                        Hello {{ $supplier->name }}. You have {{ $tasks['total_jobs'] }} job(s) to do
                        and ${{ number_format($money['due_today'], 0) }} is waiting on you.
                    </p>
                </div>

                {{-- STEP 1 · YOUR THREE JOBS --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold mb-4">Your Three Jobs — Do Them In This Order</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b">
                            <div>
                                <p class="font-medium">1. Check bureaus on your spots</p>
                                <p class="text-sm text-gray-500">This is the only thing that releases your money.</p>
                            </div>
                            <span class="text-sm font-medium {{ $tasks['spots_needing_check'] > 0 ? 'text-amber-600' : 'text-green-600' }}">
                                {{ $tasks['spots_needing_check'] }} spot(s) need checking
                            </span>
                        </div>

                        <div class="flex justify-between items-center py-2 border-b">
                            <div>
                                <p class="font-medium">2. Confirm your cards are still open</p>
                                <p class="text-sm text-gray-500">A closed card drops every spot on it.</p>
                            </div>
                            <span class="text-sm font-medium {{ $tasks['cards_needing_confirmation'] > 0 ? 'text-amber-600' : 'text-green-600' }}">
                                {{ $tasks['cards_needing_confirmation'] }} card(s) need confirming
                            </span>
                        </div>

                        <div class="flex justify-between items-center py-2">
                            <div>
                                <p class="font-medium">3. Confirm payments when they land</p>
                                <p class="text-sm text-gray-500">An unconfirmed payment looks unpaid to both of us.</p>
                            </div>
                            <span class="text-sm font-medium {{ $tasks['payments_needing_confirmation'] > 0 ? 'text-amber-600' : 'text-green-600' }}">
                                {{ $tasks['payments_needing_confirmation'] }} payment(s) waiting
                            </span>
                        </div>
                    </div>
                </div>

                {{-- MY MONEY · STEP 1 --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold mb-4">Your Money Right Now</h3>

                    <dl class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Due to you today</dt>
                            <dd class="text-2xl font-semibold text-green-600">${{ number_format($money['due_today'], 0) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Held until you check it</dt>
                            <dd class="text-2xl font-semibold text-amber-600">${{ number_format($money['held'], 0) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Paid to you so far</dt>
                            <dd class="text-2xl font-semibold">${{ number_format($money['paid_so_far'], 0) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Earned so far</dt>
                            <dd class="text-2xl font-semibold">${{ number_format($money['earned'], 0) }}</dd>
                        </div>
                    </dl>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-6 pt-6 border-t">
                        <div>
                            <dt class="text-sm text-gray-500">Your posting rate</dt>
                            <dd class="font-medium">{{ $money['posting_rate'] }}%</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Your tier</dt>
                            <dd class="font-medium">{{ $money['tier'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">The side account</dt>
                            <dd class="font-medium">${{ number_format($money['side_account'], 0) }}</dd>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>