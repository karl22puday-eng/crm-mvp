<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CardController extends Controller
{
    public function create(Supplier $supplier): View
    {
        return view('cards.create', compact('supplier'));
    }

    public function store(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'card_code' => 'required|string|max:255|unique:cards,card_code',
            'issuer_name' => 'required|string|max:255',
            'credit_limit' => 'required|numeric|min:0',
            'opened_date' => 'required|date',
            'statement_close_day' => 'required|integer|min:1|max:31',
            'slots_total' => 'nullable|integer|min:0',
            'pay_per_spot' => 'nullable|numeric|min:0',
            'status' => 'required|in:onboarding,active,closed',
            'notes' => 'nullable|string',
        ]);

        $supplier->cards()->create($validated);

        return redirect()->route('suppliers.show', $supplier)
            ->with('status', 'Card added.');
    }

    public function edit(Supplier $supplier, Card $card): View
    {
        return view('cards.edit', compact('supplier', 'card'));
    }

    public function update(Request $request, Supplier $supplier, Card $card): RedirectResponse
    {
        $validated = $request->validate([
            'card_code' => 'required|string|max:255|unique:cards,card_code,'.$card->id,
            'issuer_name' => 'required|string|max:255',
            'credit_limit' => 'required|numeric|min:0',
            'opened_date' => 'required|date',
            'statement_close_day' => 'required|integer|min:1|max:31',
            'slots_total' => 'nullable|integer|min:0',
            'pay_per_spot' => 'nullable|numeric|min:0',
            'still_open' => 'nullable|boolean',
            'balance' => 'nullable|numeric|min:0',
            'balance_date' => 'nullable|date',
            'status' => 'required|in:onboarding,active,closed',
            'notes' => 'nullable|string',
        ]);

        $card->update($validated);

        return redirect()->route('suppliers.show', $supplier)
            ->with('status', 'Card updated.');
    }

    public function destroy(Supplier $supplier, Card $card): RedirectResponse
    {
        $card->delete();

        return redirect()->route('suppliers.show', $supplier)
            ->with('status', 'Card deleted.');
    }
}