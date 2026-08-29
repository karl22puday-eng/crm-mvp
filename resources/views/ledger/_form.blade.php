<div>
    <x-input-label for="entry_date" value="Date" />
    <x-text-input id="entry_date" name="entry_date" type="date" class="block mt-1 w-full"
                  value="{{ old('entry_date', isset($ledgerEntry) ? $ledgerEntry->entry_date?->format('Y-m-d') : '') }}" required />
    <x-input-error :messages="$errors->get('entry_date')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="description" value="Description" />
    <x-text-input id="description" name="description" type="text" class="block mt-1 w-full"
                  value="{{ old('description', $ledgerEntry->description ?? '') }}" required />
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="mt-4 grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="type" value="Type" />
        <x-text-input id="type" name="type" type="text" class="block mt-1 w-full"
                      placeholder="e.g. adjustment, fee, credit" value="{{ old('type', $ledgerEntry->type ?? '') }}" />
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="amount" value="Amount" />
        <x-text-input id="amount" name="amount" type="number" step="0.01" class="block mt-1 w-full"
                      value="{{ old('amount', $ledgerEntry->amount ?? '') }}" required />
        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
    </div>
</div>

<div class="mt-4">
    <x-input-label for="direction" value="Direction" />
    <select id="direction" name="direction" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
        @php $current = old('direction', $ledgerEntry->direction ?? 'we_owe_you'); @endphp
        <option value="we_owe_you" @selected($current === 'we_owe_you')>We Owe You</option>
        <option value="you_owe_us" @selected($current === 'you_owe_us')>You Owe Us</option>
    </select>
    <x-input-error :messages="$errors->get('direction')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="raised_by" value="Raised By" />
    <x-text-input id="raised_by" name="raised_by" type="text" class="block mt-1 w-full"
                  value="{{ old('raised_by', $ledgerEntry->raised_by ?? '') }}" />
    <x-input-error :messages="$errors->get('raised_by')" class="mt-2" />
</div>

@if (isset($ledgerEntry))
    <div class="mt-4">
        <label class="flex items-center">
            <input type="checkbox" name="agreed" value="1" class="rounded border-gray-300"
                   @checked(old('agreed', $ledgerEntry->agreed))>
            <span class="ml-2 text-sm text-gray-600">Agreed by both sides?</span>
        </label>
    </div>

    <div class="mt-4 grid grid-cols-2 gap-4">
        <div>
            <x-input-label for="settled_date" value="Settled Date" />
            <x-text-input id="settled_date" name="settled_date" type="date" class="block mt-1 w-full"
                          value="{{ old('settled_date', $ledgerEntry->settled_date?->format('Y-m-d')) }}" />
        </div>
        <div>
            <x-input-label for="settlement_ref" value="Settlement Ref" />
            <x-text-input id="settlement_ref" name="settlement_ref" type="text" class="block mt-1 w-full"
                          value="{{ old('settlement_ref', $ledgerEntry->settlement_ref ?? '') }}" />
        </div>
    </div>
@endif