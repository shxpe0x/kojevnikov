<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL fulltext index requires InnoDB engine with MySQL 5.6+
        // For PostgreSQL, this would use GIN index with tsvector
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE users ADD FULLTEXT INDEX users_search_fulltext (username, bio)');
        } elseif (DB::getDriverName() === 'pgsql') {
            // PostgreSQL approach - add tsvector column and GIN index
            DB::statement(
                "ALTER TABLE users ADD COLUMN search_vector tsvector 
                GENERATED ALWAYS AS (
                    setweight(to_tsvector('russian', coalesce(username, '')), 'A') ||
                    setweight(to_tsvector('russian', coalesce(bio, '')), 'B')
                ) STORED"
            );
            DB::statement('CREATE INDEX users_search_vector_idx ON users USING GIN(search_vector)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('users_search_fulltext');
            });
        } elseif (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS users_search_vector_idx');
            DB::statement('ALTER TABLE users DROP COLUMN IF EXISTS search_vector');
        }
    }
};
