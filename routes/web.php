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

    Route::prefix('suppliers/{supplier}/cards')->name('suppliers.cards.')->group(function () {
        Route::get('/create', [\App\Http\Controllers\CardController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\CardController::class, 'store'])->name('store');
        Route::get('/{card}/edit', [\App\Http\Controllers\CardController::class, 'edit'])->name('edit');
        Route::put('/{card}', [\App\Http\Controllers\CardController::class, 'update'])->name('update');
        Route::delete('/{card}', [\App\Http\Controllers\CardController::class, 'destroy'])->name('destroy');

        Route::prefix('{card}/au-adds')->name('auAdds.')->group(function () {
            Route::get('/create', [\App\Http\Controllers\AuAddController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\AuAddController::class, 'store'])->name('store');
            Route::get('/{auAdd}/edit', [\App\Http\Controllers\AuAddController::class, 'edit'])->name('edit');
            Route::put('/{auAdd}', [\App\Http\Controllers\AuAddController::class, 'update'])->name('update');
            Route::delete('/{auAdd}', [\App\Http\Controllers\AuAddController::class, 'destroy'])->name('destroy');
        });
    });
});

// Supplier-only routes go here
Route::middleware(['auth', 'role:supplier'])->group(function () {
    //
});

require __DIR__.'/auth.php';