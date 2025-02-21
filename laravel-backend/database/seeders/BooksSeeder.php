<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Books;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Books::create([
            'title' => 'Sample Book Title',
            'author' => 'Ibrahim Author',
            'published_year' => 2025,
            'isbn' => '1234567890123',
            'status' => 'disponible',
        ]);
    }
}
