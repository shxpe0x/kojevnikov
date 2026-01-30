<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->string('cover_photo')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('location')->nullable();
            $table->string('website')->nullable();
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');
            $table->timestamp('last_seen_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'created_at']);
            $table->index('last_seen_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
