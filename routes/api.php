<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SettlementController;

Route::name('api.')->group(function () {
    // Groups
    Route::apiResource('groups', GroupController::class);

    // Members (nested under groups)
    Route::apiResource('groups.members', MemberController::class)->shallow();

    // Transactions (nested under groups)
    Route::apiResource('groups.transactions', TransactionController::class)->shallow();

    // Settlements (nested under groups)
    Route::apiResource('groups.settlements', SettlementController::class)->shallow();

    // Additional routes for business logic
    Route::get('groups/{group}/summary', [GroupController::class, 'summary']);
    Route::get('groups/{group}/balance', [GroupController::class, 'balance']);
    Route::post('transactions/{transaction}/split', [TransactionController::class, 'split']);
    Route::post('settlements/{settlement}/mark-paid', [SettlementController::class, 'markPaid']);
});
