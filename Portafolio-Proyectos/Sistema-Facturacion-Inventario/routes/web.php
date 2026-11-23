<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return redirect()->route('dashboard'); });

Route::view('dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';






Route::view('/settings/rates', 'settings.rates')->middleware(['auth', 'verified'])->name('settings.rates');
Route::view('/settings/payment-accounts', 'settings.payment-accounts')->middleware(['auth', 'verified'])->name('settings.payment-accounts');

Route::view('/inventory', 'inventory.index')->middleware(['auth', 'verified'])->name('inventory.index');
Route::view('/kardex', 'inventory.kardex')->middleware(['auth', 'verified'])->name('inventory.kardex');
Route::view('/inventory/adjustments', 'inventory.adjustments')->middleware(['auth', 'verified'])->name('inventory.adjustments');

Route::view('/pos', 'pos.index')->middleware(['auth', 'verified'])->name('pos.index');
