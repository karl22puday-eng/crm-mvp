<div>
    <x-input-label for="card_code" value="Card Code (e.g. CARD-0027)" />
    <x-text-input id="card_code" name="card_code" type="text" class="block mt-1 w-full"
                  value="{{ old('card_code', $card->card_code ?? '') }}" required />
    <x-input-error :messages="$errors->get('card_code')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="issuer_name" value="Issuer / Bank" />
    <x-text-input id="issuer_name" name="issuer_name" type="text" class="block mt-1 w-full"
                  value="{{ old('issuer_name', $card->issuer_name ?? '') }}" required />
    <x-input-error :messages="$errors->get('issuer_name')" class="mt-2" />
</div>

<div class="mt-4 grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="credit_limit" value="Credit Limit" />
        <x-text-input id="credit_limit" name="credit_limit" type="number" step="0.01" class="block mt-1 w-full"
                      value="{{ old('credit_limit', $card->credit_limit ?? '') }}" required />
        <x-input-error :messages="$errors->get('credit_limit')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="opened_date" value="Card Opened" />
        <x-text-input id="opened_date" name="opened_date" type="date" class="block mt-1 w-full"
                      value="{{ old('opened_date', isset($card) ? $card->opened_date?->format('Y-m-d') : '') }}" required />
        <x-input-error :messages="$errors->get('opened_date')" class="mt-2" />
    </div>
</div>

<div class="mt-4 grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="statement_close_day" value="Statement Close Day (1-31)" />
        <x-text-input id="statement_close_day" name="statement_close_day" type="number" min="1" max="31" class="block mt-1 w-full"
                      value="{{ old('statement_close_day', $card->statement_close_day ?? '') }}" required />
        <x-input-error :messages="$errors->get('statement_close_day')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="slots_total" value="Slots Total" />
        <x-text-input id="slots_total" name="slots_total" type="number" min="0" class="block mt-1 w-full"
                      value="{{ old('slots_total', $card->slots_total ?? '') }}" />
        <x-input-error :messages="$errors->get('slots_total')" class="mt-2" />
    </div>
</div>

<div class="mt-4">
    <x-input-label for="pay_per_spot" value="Pay Per Spot" />
    <x-text-input id="pay_per_spot" name="pay_per_spot" type="number" step="0.01" class="block mt-1 w-full"
                  value="{{ old('pay_per_spot', $card->pay_per_spot ?? '') }}" />
    <x-input-error :messages="$errors->get('pay_per_spot')" class="mt-2" />
</div>

@if (isset($card))
    <div class="mt-4 grid grid-cols-2 gap-4">
        <div>
            <x-input-label for="balance" value="Balance Today" />
            <x-text-input id="balance" name="balance" type="number" step="0.01" class="block mt-1 w-full"
                          value="{{ old('balance', $card->balance ?? '') }}" />
            <x-input-error :messages="$errors->get('balance')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="balance_date" value="Balance Date" />
            <x-text-input id="balance_date" name="balance_date" type="date" class="block mt-1 w-full"
                          value="{{ old('balance_date', $card->balance_date?->format('Y-m-d')) }}" />
            <x-input-error :messages="$errors->get('balance_date')" class="mt-2" />
        </div>
    </div>

    <div class="mt-4">
        <label class="flex items-center">
            <input type="checkbox" name="still_open" value="1" class="rounded border-gray-300"
                   @checked(old('still_open', $card->still_open))>
            <span class="ml-2 text-sm text-gray-600">Card still open?</span>
        </label>
    </div>
@endif

<div class="mt-4">
    <x-input-label for="status" value="Status" />
    <select id="status" name="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
        @php $currentStatus = old('status', $card->status ?? 'onboarding'); @endphp
        <option value="onboarding" @selected($currentStatus === 'onboarding')>Onboarding</option>
        <option value="active" @selected($currentStatus === 'active')>Active</option>
        <option value="closed" @selected($currentStatus === 'closed')>Closed</option>
    </select>
    <x-input-error :messages="$errors->get('status')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="notes" value="Notes" />
    <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('notes', $card->notes ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
</div>