<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Composite index for finding active users by username
            $table->index(['username', 'status']);
            
            // Fulltext index for search functionality
            $table->fullText(['username', 'first_name', 'last_name']);
            $table->fullText('bio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['username', 'status']);
            $table->dropFullText(['username', 'first_name', 'last_name']);
            $table->dropFullText(['bio']);
        });
    }
};
