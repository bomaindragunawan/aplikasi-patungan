<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\Member;
use App\Models\Transaction;
use App\Models\ExpenseSplit;

class PatunganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample group (keluarga)
        $group = Group::create([
            'name' => 'Patungan Liburan Bersama',
            'description' => 'Arisan untuk liburan akhir tahun ke Bali',
            'currency' => 'IDR',
        ]);

        // Create members
        $members = [
            Member::create([
                'group_id' => $group->id,
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'phone' => '08123456789',
            ]),
            Member::create([
                'group_id' => $group->id,
                'name' => 'Sri Wijaya',
                'email' => 'sri@example.com',
                'phone' => '08234567890',
            ]),
            Member::create([
                'group_id' => $group->id,
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad@example.com',
                'phone' => '08345678901',
            ]),
            Member::create([
                'group_id' => $group->id,
                'name' => 'Putri Indah',
                'email' => 'putri@example.com',
                'phone' => '08456789012',
            ]),
        ];

        // Create sample transactions
        $transaction1 = Transaction::create([
            'group_id' => $group->id,
            'payer_id' => $members[0]->id,
            'description' => 'Bayar tiket pesawat',
            'amount' => 4000000,
            'category' => 'Transportasi',
            'transaction_date' => now(),
        ]);

        // Create expense splits for transaction 1 (split equally)
        foreach ($members as $member) {
            ExpenseSplit::create([
                'transaction_id' => $transaction1->id,
                'member_id' => $member->id,
                'amount' => 1000000,
            ]);
        }

        // Create another sample transaction
        $transaction2 = Transaction::create([
            'group_id' => $group->id,
            'payer_id' => $members[1]->id,
            'description' => 'Bayar hotel dan akomodasi',
            'amount' => 3000000,
            'category' => 'Akomodasi',
            'transaction_date' => now(),
        ]);

        // Create expense splits for transaction 2
        foreach ($members as $member) {
            ExpenseSplit::create([
                'transaction_id' => $transaction2->id,
                'member_id' => $member->id,
                'amount' => 750000,
            ]);
        }

        // Create one more group for example
        $group2 = Group::create([
            'name' => 'Patungan Arisan Bulanan',
            'description' => 'Arisan rutin bulanan keluarga',
            'currency' => 'IDR',
        ]);

        $members2 = [
            Member::create([
                'group_id' => $group2->id,
                'name' => 'Ibu Siti',
                'email' => 'ibu.siti@example.com',
            ]),
            Member::create([
                'group_id' => $group2->id,
                'name' => 'Ibu Erna',
                'email' => 'ibu.erna@example.com',
            ]),
        ];
    }
}
