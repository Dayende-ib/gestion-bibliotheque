<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Members;

class MembersSeeder extends Seeder
{
    public function run()
    {
        Members::create([
            'user_id' => 1, // Assuming a user with ID 1 exists
            'phone' => '06274972',
            'address' => 'secteur 22',
            'membership_number' => 'MEM' . uniqid(), // Generate a unique membership number
            'join_date' => now(),
            'expiry_date' => now()->addYear(),
            'status' => 'Active',
        ]);
    }
}