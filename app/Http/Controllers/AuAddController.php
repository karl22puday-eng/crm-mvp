<?php

namespace App\Http\Controllers;

use App\Models\AuAdd;
use App\Models\Card;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuAddController extends Controller
{
    public function create(Supplier $supplier, Card $card): View
    {
        return view('au-adds.create', compact('supplier', 'card'));
    }

    public function store(Request $request, Supplier $supplier, Card $card): RedirectResponse
    {
        $validated = $request->validate([
            'add_ref' => 'required|string|max:255|unique:au_adds,add_ref',
            'order_ref' => 'nullable|string|max:255',
            'client_name' => 'required|string|max:255',
            'date_added' => 'required|date',
            'rate' => 'nullable|numeric|min:0',
        ]);

        $card->auAdds()->create($validated);

        return redirect()->route('suppliers.show', $supplier)
            ->with('status', 'Spot added.');
    }

    public function edit(Supplier $supplier, Card $card, AuAdd $auAdd): View
    {
        return view('au-adds.edit', compact('supplier', 'card', 'auAdd'));
    }

    public function update(Request $request, Supplier $supplier, Card $card, AuAdd $auAdd): RedirectResponse
    {
        $validated = $request->validate([
            'add_ref' => 'required|string|max:255|unique:au_adds,add_ref,'.$auAdd->id,
            'order_ref' => 'nullable|string|max:255',
            'client_name' => 'required|string|max:255',
            'date_added' => 'required|date',
            'experian_showing' => 'nullable|boolean',
            'equifax_showing' => 'nullable|boolean',
            'transunion_showing' => 'nullable|boolean',
            'date_checked' => 'nullable|date',
            'source' => 'nullable|string|max:255',
            'rate' => 'nullable|numeric|min:0',
            'payout_status' => 'required|in:pending,held,due,paid',
            'paid' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        // Auto-compute minimum_met: at least 2 of 3 bureaus showing
        $bureausShowing = collect([
            $validated['experian_showing'] ?? false,
            $validated['equifax_showing'] ?? false,
            $validated['transunion_showing'] ?? false,
        ])->filter()->count();

        $validated['minimum_met'] = $bureausShowing >= 2;

        // Auto-compute payout amount: rate applies only once minimum is met
        $validated['payout_amount'] = $validated['minimum_met'] ? ($validated['rate'] ?? 0) : 0;

        $auAdd->update($validated);

        return redirect()->route('suppliers.show', $supplier)
            ->with('status', 'Spot updated.');
    }

    public function destroy(Supplier $supplier, Card $card, AuAdd $auAdd): RedirectResponse
    {
        $auAdd->delete();

        return redirect()->route('suppliers.show', $supplier)
            ->with('status', 'Spot deleted.');
    }
}