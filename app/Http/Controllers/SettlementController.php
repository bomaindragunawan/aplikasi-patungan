<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Settlement;
use Illuminate\Http\Request;

class SettlementController extends Controller
{
    /**
     * Display settlements of a group.
     */
    public function index(Group $group)
    {
        return response()->json(
            $group->settlements()
                ->with('fromMember', 'toMember')
                ->latest()
                ->get()
        );
    }

    /**
     * Store a newly created settlement.
     */
    public function store(Request $request, Group $group)
    {
        $validated = $request->validate([
            'from_member_id' => 'required|exists:members,id',
            'to_member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $settlement = $group->settlements()->create([
            'from_member_id' => $validated['from_member_id'],
            'to_member_id' => $validated['to_member_id'],
            'amount' => $validated['amount'],
            'status' => 'pending',
        ]);

        return response()->json($settlement->load('fromMember', 'toMember'), 201);
    }

    /**
     * Display the specified settlement.
     */
    public function show(Settlement $settlement)
    {
        return response()->json($settlement->load('group', 'fromMember', 'toMember'));
    }

    /**
     * Update the specified settlement.
     */
    public function update(Request $request, Settlement $settlement)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:pending,completed',
            'amount' => 'sometimes|numeric|min:0.01',
        ]);

        $settlement->update($validated);
        return response()->json($settlement);
    }

    /**
     * Remove the specified settlement.
     */
    public function destroy(Settlement $settlement)
    {
        $settlement->delete();
        return response()->json(null, 204);
    }

    /**
     * Mark settlement as paid/completed.
     */
    public function markPaid(Settlement $settlement)
    {
        $settlement->update([
            'status' => 'completed',
            'settled_date' => now(),
        ]);

        return response()->json($settlement, 200);
    }
}
