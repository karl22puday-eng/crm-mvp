<?php

namespace App\Http\Controllers;

use App\Models\CrmApplication;
use App\Models\Supplier;
use App\Services\ApprovalEngine;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function create(Supplier $supplier): View
    {
        return view('applications.create', compact('supplier'));
    }

    public function store(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'annual_revenue' => 'nullable|numeric|min:0',
            'annual_net_profit' => 'nullable|numeric|min:0',
            'avg_monthly_deposits' => 'nullable|numeric|min:0',
        ]);

        if (empty(array_filter($validated, fn ($v) => $v !== null))) {
            return back()->withErrors(['annual_revenue' => 'Enter at least one financial metric.'])->withInput();
        }

        $application = $supplier->applications()->create(['status' => 'submitted']);
        $application->financialProfile()->create($validated);

        return redirect()->route('suppliers.applications.show', [$supplier, $application]);
    }

    public function show(Supplier $supplier, CrmApplication $application): View
    {
        $application->load('financialProfile', 'approvalCalculations');

        return view('applications.show', compact('supplier', 'application'));
    }

    public function calculate(Supplier $supplier, CrmApplication $application, ApprovalEngine $engine): RedirectResponse
    {
        $engine->calculateForApplication($application);

        $application->update(['status' => 'calculated']);

        return redirect()->route('suppliers.applications.show', [$supplier, $application])
            ->with('status', 'Calculation complete.');
    }
}