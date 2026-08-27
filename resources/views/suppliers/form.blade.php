<div>
    <x-input-label for="name" value="Name" />
    <x-text-input id="name" name="name" type="text" class="block mt-1 w-full"
                  value="{{ old('name', $supplier->name ?? '') }}" required />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="email" value="Email" />
    <x-text-input id="email" name="email" type="email" class="block mt-1 w-full"
                  value="{{ old('email', $supplier->email ?? '') }}" />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="phone" value="Phone" />
    <x-text-input id="phone" name="phone" type="text" class="block mt-1 w-full"
                  value="{{ old('phone', $supplier->phone ?? '') }}" />
    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="tier" value="Tier" />
    <select id="tier" name="tier" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
        @php $current = old('tier', $supplier->tier ?? 'under_review'); @endphp
        <option value="under_review" @selected($current === 'under_review')>Under Review</option>
        <option value="standard" @selected($current === 'standard')>Standard</option>
        <option value="preferred" @selected($current === 'preferred')>Preferred</option>
    </select>
    <x-input-error :messages="$errors->get('tier')" class="mt-2" />
</div>