<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display members of a group.
     */
    public function index(Group $group)
    {
        return response()->json($group->members()->with('transactions', 'expenseSplits')->get());
    }

    /**
     * Store a newly created member in a group.
     */
    public function store(Request $request, Group $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        $member = $group->members()->create($validated);
        return response()->json($member, 201);
    }

    /**
     * Display the specified member.
     */
    public function show(Member $member)
    {
        return response()->json($member->load('group', 'transactions', 'expenseSplits'));
    }

    /**
     * Update the specified member.
     */
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        $member->update($validated);
        return response()->json($member);
    }

    /**
     * Remove the specified member.
     */
    public function destroy(Member $member)
    {
        $member->delete();
        return response()->json(null, 204);
    }
}
