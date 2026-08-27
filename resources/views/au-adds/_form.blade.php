<div>
    <x-input-label for="add_ref" value="Add Ref (e.g. ADD-0001)" />
    <x-text-input id="add_ref" name="add_ref" type="text" class="block mt-1 w-full"
                  value="{{ old('add_ref', $auAdd->add_ref ?? '') }}" required />
    <x-input-error :messages="$errors->get('add_ref')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="order_ref" value="Order Ref" />
    <x-text-input id="order_ref" name="order_ref" type="text" class="block mt-1 w-full"
                  value="{{ old('order_ref', $auAdd->order_ref ?? '') }}" />
    <x-input-error :messages="$errors->get('order_ref')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="client_name" value="Client Name" />
    <x-text-input id="client_name" name="client_name" type="text" class="block mt-1 w-full"
                  value="{{ old('client_name', $auAdd->client_name ?? '') }}" required />
    <x-input-error :messages="$errors->get('client_name')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="date_added" value="Date Added" />
    <x-text-input id="date_added" name="date_added" type="date" class="block mt-1 w-full"
                  value="{{ old('date_added', isset($auAdd) ? $auAdd->date_added?->format('Y-m-d') : '') }}" required />
    <x-input-error :messages="$errors->get('date_added')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="rate" value="Agreed Rate ($)" />
    <x-text-input id="rate" name="rate" type="number" step="0.01" class="block mt-1 w-full"
                  value="{{ old('rate', $auAdd->rate ?? '') }}" />
    <x-input-error :messages="$errors->get('rate')" class="mt-2" />
</div>

@if (isset($auAdd))
    <div class="mt-6 pt-4 border-t">
        <h4 class="font-medium text-sm text-gray-700 mb-2">Bureau Check</h4>
        <div class="grid grid-cols-3 gap-4">
            <label class="flex items-center">
                <input type="checkbox" name="experian_showing" value="1" class="rounded border-gray-300"
                       @checked(old('experian_showing', $auAdd->experian_showing))>
                <span class="ml-2 text-sm text-gray-600">Experian</span>
            </label>
            <label class="flex items-center">
                <input type="checkbox" name="equifax_showing" value="1" class="rounded border-gray-300"
                       @checked(old('equifax_showing', $auAdd->equifax_showing))>
                <span class="ml-2 text-sm text-gray-600">Equifax</span>
            </label>
            <label class="flex items-center">
                <input type="checkbox" name="transunion_showing" value="1" class="rounded border-gray-300"
                       @checked(old('transunion_showing', $auAdd->transunion_showing))>
                <span class="ml-2 text-sm text-gray-600">TransUnion</span>
            </label>
        </div>
        <p class="text-xs text-gray-500 mt-1">Pays only once at least 2 of 3 are checked.</p>
    </div>

    <div class="mt-4 grid grid-cols-2 gap-4">
        <div>
            <x-input-label for="date_checked" value="Date I Checked" />
            <x-text-input id="date_checked" name="date_checked" type="date" class="block mt-1 w-full"
                          value="{{ old('date_checked', $auAdd->date_checked?->format('Y-m-d')) }}" />
            <x-input-error :messages="$errors->get('date_checked')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="source" value="Where I Saw It" />
            <x-text-input id="source" name="source" type="text" class="block mt-1 w-full"
                          value="{{ old('source', $auAdd->source ?? '') }}" />
            <x-input-error :messages="$errors->get('source')" class="mt-2" />
        </div>
    </div>

    <div class="mt-4">
        <x-input-label for="payout_status" value="Payout Status" />
        <select id="payout_status" name="payout_status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
            @php $currentPS = old('payout_status', $auAdd->payout_status ?? 'pending'); @endphp
            <option value="pending" @selected($currentPS === 'pending')>Pending</option>
            <option value="held" @selected($currentPS === 'held')>Held</option>
            <option value="due" @selected($currentPS === 'due')>Due</option>
            <option value="paid" @selected($currentPS === 'paid')>Paid</option>
        </select>
        <x-input-error :messages="$errors->get('payout_status')" class="mt-2" />
    </div>

    <div class="mt-4">
        <label class="flex items-center">
            <input type="checkbox" name="paid" value="1" class="rounded border-gray-300"
                   @checked(old('paid', $auAdd->paid))>
            <span class="ml-2 text-sm text-gray-600">Paid?</span>
        </label>
    </div>

    <div class="mt-4">
        <x-input-label for="notes" value="Notes" />
        <textarea id="notes" name="notes" rows="2" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('notes', $auAdd->notes ?? '') }}</textarea>
    </div>
@endif