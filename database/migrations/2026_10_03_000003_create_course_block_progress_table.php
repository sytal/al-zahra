<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_block_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_block_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->timestamp('completed_at')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
            $table->unique(['enrollment_id', 'course_block_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_block_progress');
    }
};
