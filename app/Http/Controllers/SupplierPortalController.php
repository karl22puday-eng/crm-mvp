<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierPortalController extends Controller
{
    protected function supplierOrAbort(Request $request)
    {
        $supplier = $request->user()->supplier;

        if (! $supplier) {
            abort(403, 'No supplier record linked to your account.');
        }

        return $supplier;
    }

    public function spots(Request $request): View
    {
        $supplier = $this->supplierOrAbort($request);
        $supplier->load('cards.auAdds');

        $spots = $supplier->cards->flatMap(function ($card) {
            return $card->auAdds->map(function ($spot) use ($card) {
                $spot->card_label = $card->issuer_name.' ('.$card->card_code.')';

                return $spot;
            });
        });

        return view('portal.spots', compact('spots'));
    }

    public function cards(Request $request): View
    {
        $supplier = $this->supplierOrAbort($request);
        $supplier->load('cards.auAdds');

        return view('portal.cards', ['cards' => $supplier->cards]);
    }

    public function payments(Request $request): View
    {
        $supplier = $this->supplierOrAbort($request);
        $supplier->load('payments');

        return view('portal.payments', ['payments' => $supplier->payments]);
    }
}