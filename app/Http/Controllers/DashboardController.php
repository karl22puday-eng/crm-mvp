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

            return view('dashboard.supplier', compact('supplier'));
        }

        return view('dashboard.staff');
    }
}