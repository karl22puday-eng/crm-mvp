<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function create(Supplier $supplier): View
    {
        return view('payments.create', compact('supplier'));
    }

    public function store(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'payment_ref' => 'required|string|max:255|unique:payments,payment_ref',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'covers' => 'nullable|string|max:255',
            'comment' => 'nullable|string',
        ]);

        $supplier->payments()->create($validated);

        return redirect()->route('suppliers.show', $supplier)
            ->with('status', 'Payment recorded.');
    }

    public function edit(Supplier $supplier, Payment $payment): View
    {
        return view('payments.edit', compact('supplier', 'payment'));
    }

    public function update(Request $request, Supplier $supplier, Payment $payment): RedirectResponse
    {
                $validated = $request->validate([
            'payment_ref' => 'required|string|max:255|unique:payments,payment_ref,'.$payment->id,
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'covers' => 'nullable|string|max:255',
            'confirmed' => 'nullable|boolean',
            'confirmed_date' => 'nullable|date',
            'amount_disputed' => 'nullable|boolean',
            'comment' => 'nullable|string',
        ]);

        $payment->update($validated);

        return redirect()->route('suppliers.show', $supplier)
            ->with('status', 'Payment updated.');
    }

    public function destroy(Supplier $supplier, Payment $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->route('suppliers.show', $supplier)
            ->with('status', 'Payment deleted.');
    }
}