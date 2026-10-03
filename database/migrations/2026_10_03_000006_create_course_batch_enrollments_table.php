<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_batch_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('enrollment_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('roll_number')->nullable()->unique();
            $table->string('status');
            $table->unsignedInteger('waitlist_position')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_batch_enrollments');
    }
};
