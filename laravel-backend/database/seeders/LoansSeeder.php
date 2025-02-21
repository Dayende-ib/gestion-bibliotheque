<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Loans;

class LoansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Loans::create([
            'book_id' => 1, // Assuming a book with ID 1 exists
            'member_id' => 1, // Assuming a member with ID 1 exists
            'borrowed_at' => now(), // Use borrowed_at instead of loan_date
            'due_date' => now()->addDays(14), // Set due date to 14 days from now
            'status' => 'Borrowed',
        ]);
    }
}
