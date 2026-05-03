<?php

namespace App\Http\Controllers;

use App\Models\ExpenseSplit;
use App\Models\Group;
use App\Models\Member;
use App\Models\Settlement;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatunganController extends Controller
{
    public function index()
    {
        $groups = Group::withCount('members')->get();
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'currency' => 'nullable|string|max:10',
        ]);

        Group::create($validated);

        return redirect()->route('groups.index')->with('success', 'Group berhasil dibuat.');
    }

    public function show(Group $group)
    {
        $group->load([
            'members',
            'transactions.payer',
            'transactions.expenseSplits.member',
            'settlements.fromMember',
            'settlements.toMember',
        ]);

        $balances = $group->members->map(function ($member) {
            $paid = $member->transactions->sum('amount');
            $spent = $member->expenseSplits->sum('amount');
            return [
                'member' => $member,
                'paid' => $paid,
                'spent' => $spent,
                'balance' => $paid - $spent,
            ];
        });

        return view('groups.show', compact('group', 'balances'));
    }

    public function edit(Group $group)
    {
        return view('groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'currency' => 'nullable|string|max:10',
        ]);

        $group->update($validated);

        return redirect()->route('groups.show', $group)->with('success', 'Group berhasil diperbarui.');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Group berhasil dihapus.');
    }

    public function storeMember(Request $request, Group $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
        ]);

        $group->members()->create($validated);

        return redirect()->route('groups.show', $group)->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function storeTransaction(Request $request, Group $group)
    {
        $validated = $request->validate([
            'payer_id' => 'required|exists:members,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $group) {
            $transaction = $group->transactions()->create([
                'payer_id' => $validated['payer_id'],
                'description' => $validated['description'],
                'amount' => $validated['amount'],
                'category' => $validated['category'] ?? null,
                'transaction_date' => now(),
            ]);

            $members = $group->members;
            $splitAmount = $validated['amount'] / max($members->count(), 1);

            foreach ($members as $member) {
                ExpenseSplit::create([
                    'transaction_id' => $transaction->id,
                    'member_id' => $member->id,
                    'amount' => $splitAmount,
                ]);
            }
        });

        return redirect()->route('groups.show', $group)->with('success', 'Transaksi berhasil disimpan.');
    }

    public function storeSettlement(Request $request, Group $group)
    {
        $validated = $request->validate([
            'from_member_id' => 'required|exists:members,id',
            'to_member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $group->settlements()->create([
            'from_member_id' => $validated['from_member_id'],
            'to_member_id' => $validated['to_member_id'],
            'amount' => $validated['amount'],
            'status' => 'pending',
        ]);

        return redirect()->route('groups.show', $group)->with('success', 'Settlement berhasil ditambahkan.');
    }

    public function destroyMember(Member $member)
    {
        $group = $member->group;
        $member->delete();

        return redirect()->route('groups.show', $group)->with('success', 'Anggota berhasil dihapus.');
    }

    public function destroyTransaction(Transaction $transaction)
    {
        $group = $transaction->group;
        $transaction->expenseSplits()->delete();
        $transaction->delete();

        return redirect()->route('groups.show', $group)->with('success', 'Transaksi berhasil dihapus.');
    }

    public function destroySettlement(Settlement $settlement)
    {
        $group = $settlement->group;
        $settlement->delete();

        return redirect()->route('groups.show', $group)->with('success', 'Settlement berhasil dihapus.');
    }
}
