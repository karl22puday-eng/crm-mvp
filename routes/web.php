<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Staff-only routes go here
Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
});

// Supplier-only routes go here
Route::middleware(['auth', 'role:supplier'])->group(function () {
    //
});

require __DIR__.'/auth.php';