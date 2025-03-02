<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
			$table->timestamp('borrowed_at')->useCurrent();
            $table->date('due_date');
            $table->timestamp('returned_at')->nullable();
            $table->enum('status', ['Borrowed', 'Returned', 'Overdue'])->default('Borrowed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('loans')->delete(); // Delete existing loans before dropping the table
        Schema::dropIfExists('loans');
    }
};
