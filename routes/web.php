<?php

use App\Http\Controllers\PatunganController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('groups.index');
});

Route::resource('groups', PatunganController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
Route::post('groups/{group}/members', [PatunganController::class, 'storeMember'])->name('groups.members.store');
Route::post('groups/{group}/transactions', [PatunganController::class, 'storeTransaction'])->name('groups.transactions.store');
Route::post('groups/{group}/settlements', [PatunganController::class, 'storeSettlement'])->name('groups.settlements.store');
Route::delete('members/{member}', [PatunganController::class, 'destroyMember'])->name('members.destroy');
Route::delete('transactions/{transaction}', [PatunganController::class, 'destroyTransaction'])->name('transactions.destroy');
Route::delete('settlements/{settlement}', [PatunganController::class, 'destroySettlement'])->name('settlements.destroy');
