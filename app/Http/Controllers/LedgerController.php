<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LedgerController extends Controller
{
    public function create(Supplier $supplier): View
    {
        return view('ledger.create', compact('supplier'));
    }

    public function store(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'item_ref' => 'nullable|string|max:255',
            'entry_date' => 'required|date',
            'description' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'direction' => 'required|in:we_owe_you,you_owe_us',
            'amount' => 'required|numeric|min:0',
            'raised_by' => 'nullable|string|max:255',
        ]);

        $supplier->ledgerEntries()->create($validated);

        return redirect()->route('suppliers.show', $supplier)
            ->with('status', 'Ledger entry added.');
    }

    public function edit(Supplier $supplier, Ledger $ledgerEntry): View
    {
        return view('ledger.edit', compact('supplier', 'ledgerEntry'));
    }

    public function update(Request $request, Supplier $supplier, Ledger $ledgerEntry): RedirectResponse
    {
        $validated = $request->validate([
            'item_ref' => 'nullable|string|max:255',
            'entry_date' => 'required|date',
            'description' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'direction' => 'required|in:we_owe_you,you_owe_us',
            'amount' => 'required|numeric|min:0',
            'settled_date' => 'nullable|date',
            'raised_by' => 'nullable|string|max:255',
            'settlement_ref' => 'nullable|string|max:255',
        ]);

        $validated['agreed'] = $request->boolean('agreed');

        $ledgerEntry->update($validated);

        return redirect()->route('suppliers.show', $supplier)
            ->with('status', 'Ledger entry updated.');
    }

    public function destroy(Supplier $supplier, Ledger $ledgerEntry): RedirectResponse
    {
        $ledgerEntry->delete();

        return redirect()->route('suppliers.show', $supplier)
            ->with('status', 'Ledger entry deleted.');
    }
}