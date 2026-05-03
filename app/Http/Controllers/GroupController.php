<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Group::with('members', 'transactions')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'currency' => 'nullable|string|max:10',
        ]);

        $group = Group::create($validated);
        return response()->json($group, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        return response()->json($group->load('members', 'transactions', 'settlements'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'currency' => 'nullable|string|max:10',
        ]);

        $group->update($validated);
        return response()->json($group);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return response()->json(null, 204);
    }

    /**
     * Get group summary with balance information.
     */
    public function summary(Group $group)
    {
        $members = $group->members()->with(['expenseSplits', 'transactions'])->get();
        
        $summary = [
            'group' => $group,
            'total_transactions' => $group->transactions()->sum('amount'),
            'members_count' => $members->count(),
            'balances' => $members->map(function ($member) {
                $paid = $member->transactions()->sum('amount');
                $spent = $member->expenseSplits()->sum('amount');
                return [
                    'member_id' => $member->id,
                    'member_name' => $member->name,
                    'paid' => (float) $paid,
                    'spent' => (float) $spent,
                    'balance' => (float) ($paid - $spent),
                ];
            }),
        ];

        return response()->json($summary);
    }

    /**
     * Get detailed balance information.
     */
    public function balance(Group $group)
    {
        // Implementation untuk menghitung siapa hutang ke siapa
        $members = $group->members()->with('transactions', 'expenseSplits')->get();
        
        $balances = [];
        foreach ($members as $member) {
            $paid = $member->transactions()->sum('amount') ?? 0;
            $spent = $member->expenseSplits()->sum('amount') ?? 0;
            $balances[$member->id] = [
                'member' => $member,
                'balance' => $paid - $spent,
            ];
        }

        return response()->json($balances);
    }
}
