<div>
    <x-input-label for="payment_ref" value="Payment Ref (e.g. PAY-0001)" />
    <x-text-input id="payment_ref" name="payment_ref" type="text" class="block mt-1 w-full"
                  value="{{ old('payment_ref', $payment->payment_ref ?? '') }}" required />
    <x-input-error :messages="$errors->get('payment_ref')" class="mt-2" />
</div>

<div class="mt-4 grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="payment_date" value="Payment Date" />
        <x-text-input id="payment_date" name="payment_date" type="date" class="block mt-1 w-full"
                      value="{{ old('payment_date', isset($payment) ? $payment->payment_date?->format('Y-m-d') : '') }}" required />
        <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="amount" value="Amount" />
        <x-text-input id="amount" name="amount" type="number" step="0.01" class="block mt-1 w-full"
                      value="{{ old('amount', $payment->amount ?? '') }}" required />
        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
    </div>
</div>

<div class="mt-4">
    <x-input-label for="covers" value="Covers (which spots/period)" />
    <x-text-input id="covers" name="covers" type="text" class="block mt-1 w-full"
                  value="{{ old('covers', $payment->covers ?? '') }}" />
    <x-input-error :messages="$errors->get('covers')" class="mt-2" />
</div>

@if (isset($payment))
    <div class="mt-4">
        <label class="flex items-center">
            <input type="checkbox" name="confirmed" value="1" class="rounded border-gray-300"
                   @checked(old('confirmed', $payment->confirmed))>
            <span class="ml-2 text-sm text-gray-600">Supplier confirmed receipt?</span>
        </label>
    </div>

    <div class="mt-4">
        <x-input-label for="confirmed_date" value="Confirmed Date" />
        <x-text-input id="confirmed_date" name="confirmed_date" type="date" class="block mt-1 w-full"
                      value="{{ old('confirmed_date', $payment->confirmed_date?->format('Y-m-d')) }}" />
    </div>

    <div class="mt-4">
        <label class="flex items-center">
            <input type="checkbox" name="amount_disputed" value="1" class="rounded border-gray-300"
                   @checked(old('amount_disputed', $payment->amount_disputed))>
            <span class="ml-2 text-sm text-gray-600">Amount disputed by supplier?</span>
        </label>
    </div>
@endif

<div class="mt-4">
    <x-input-label for="comment" value="Comment" />
    <textarea id="comment" name="comment" rows="2" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('comment', $payment->comment ?? '') }}</textarea>
</div>