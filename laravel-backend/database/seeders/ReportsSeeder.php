<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reports;

class ReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reports::create([
            'member_id' => 1, // Assuming a member with ID 1 exists
            'report_date' => now(),
            'description' => 'Sample report description',
        ]);
    }
}
