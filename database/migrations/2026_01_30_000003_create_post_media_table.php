<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_media', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['image', 'video', 'document']);
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type');
            $table->integer('file_size');
            $table->integer('order')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['post_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_media');
    }
};
