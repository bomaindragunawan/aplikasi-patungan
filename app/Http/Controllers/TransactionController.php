<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Transaction;
use App\Models\ExpenseSplit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display transactions of a group.
     */
    public function index(Group $group)
    {
        return response()->json(
            $group->transactions()
                ->with('payer', 'expenseSplits.member')
                ->latest()
                ->get()
        );
    }

    /**
     * Store a newly created transaction.
     */
    public function store(Request $request, Group $group)
    {
        $validated = $request->validate([
            'payer_id' => 'required|exists:members,id',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'nullable|string',
            'transaction_date' => 'nullable|date',
            'splits' => 'nullable|array',
            'splits.*.member_id' => 'required|exists:members,id',
            'splits.*.amount' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($validated, $group) {
            $transaction = $group->transactions()->create([
                'payer_id' => $validated['payer_id'],
                'description' => $validated['description'],
                'amount' => $validated['amount'],
                'category' => $validated['category'] ?? null,
                'transaction_date' => $validated['transaction_date'] ?? now(),
            ]);

            // Create expense splits if provided
            if (!empty($validated['splits'])) {
                foreach ($validated['splits'] as $split) {
                    ExpenseSplit::create([
                        'transaction_id' => $transaction->id,
                        'member_id' => $split['member_id'],
                        'amount' => $split['amount'],
                    ]);
                }
            } else {
                // If no splits provided, create equal split amongst all members
                $members = $group->members()->count();
                $splitAmount = $validated['amount'] / $members;
                
                foreach ($group->members as $member) {
                    ExpenseSplit::create([
                        'transaction_id' => $transaction->id,
                        'member_id' => $member->id,
                        'amount' => $splitAmount,
                    ]);
                }
            }

            return response()->json($transaction->load('payer', 'expenseSplits.member'), 201);
        });
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        return response()->json($transaction->load('group', 'payer', 'expenseSplits.member'));
    }

    /**
     * Update the specified transaction.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'description' => 'sometimes|required|string',
            'amount' => 'sometimes|required|numeric|min:0.01',
            'category' => 'nullable|string',
        ]);

        $transaction->update($validated);
        return response()->json($transaction);
    }

    /**
     * Remove the specified transaction.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->expenseSplits()->delete();
        $transaction->delete();
        return response()->json(null, 204);
    }

    /**
     * Create expense splits for a transaction.
     */
    public function split(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'splits' => 'required|array',
            'splits.*.member_id' => 'required|exists:members,id',
            'splits.*.amount' => 'required|numeric|min:0',
        ]);

        // Delete existing splits
        $transaction->expenseSplits()->delete();

        // Create new splits
        foreach ($validated['splits'] as $split) {
            ExpenseSplit::create([
                'transaction_id' => $transaction->id,
                'member_id' => $split['member_id'],
                'amount' => $split['amount'],
            ]);
        }

        return response()->json($transaction->load('expenseSplits.member'), 200);
    }
}
