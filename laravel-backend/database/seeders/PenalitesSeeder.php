<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Penalites;

class PenalitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Penalites::create([
            'loan_id' => 1, // Assuming a loan with ID 1 exists
            'member_id' => 1, // Assuming a member with ID 1 exists
            'start_date' => now(), // Current date as start date
            'end_date' => now()->addDays(30), // 30 days from now as end date
            'amount' => 5.00,
            'status' => 'non paye',
        ]);
    }
}
