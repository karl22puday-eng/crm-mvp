<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->role === 'supplier') {
            $supplier = $user->supplier;

            if (! $supplier) {
                return view('dashboard.supplier', ['supplier' => null]);
            }

            $supplier->load('cards.auAdds', 'payments', 'ledgerEntries');

            $allSpots = $supplier->cards->flatMap->auAdds;
            $totalSpots = $allSpots->count();
            $minimumMetSpots = $allSpots->where('minimum_met', true);

            $dueToday = $allSpots->where('minimum_met', true)->where('payout_status', 'due')->sum('payout_amount');
            $held = $allSpots->where('minimum_met', false)->sum('rate');
            $paidSoFar = $allSpots->where('paid', true)->sum('payout_amount');
            $earned = $dueToday + $paidSoFar;

            $postingRate = $totalSpots > 0
                ? round(($minimumMetSpots->count() / $totalSpots) * 100)
                : 0;

            $tier = match (true) {
                $postingRate >= 80 => 'Preferred',
                $postingRate >= 50 => 'Standard',
                default => 'Under review',
            };

            $sideAccount = $supplier->ledgerEntries->where('direction', 'we_owe_you')->whereNull('settled_date')->sum('amount')
                - $supplier->ledgerEntries->where('direction', 'you_owe_us')->whereNull('settled_date')->sum('amount');

            $spotsNeedingCheck = $allSpots->where('minimum_met', false)->count();
            $cardsNeedingConfirmation = $supplier->cards->whereNull('still_open')->count();
            $paymentsNeedingConfirmation = $supplier->payments->whereNull('confirmed')->count();

            $totalJobs = $spotsNeedingCheck + $cardsNeedingConfirmation + $paymentsNeedingConfirmation;

            $money = [
                'due_today' => $dueToday,
                'held' => $held,
                'paid_so_far' => $paidSoFar,
                'earned' => $earned,
                'posting_rate' => $postingRate,
                'tier' => $tier,
                'side_account' => $sideAccount,
            ];

            $tasks = [
                'spots_needing_check' => $spotsNeedingCheck,
                'cards_needing_confirmation' => $cardsNeedingConfirmation,
                'payments_needing_confirmation' => $paymentsNeedingConfirmation,
                'total_jobs' => $totalJobs,
            ];

            return view('dashboard.supplier', compact('supplier', 'money', 'tasks'));
        }

        return view('dashboard.staff');
    }
}