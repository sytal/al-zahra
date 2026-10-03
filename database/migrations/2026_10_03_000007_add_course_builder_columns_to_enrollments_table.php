<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreignId('course_batch_id')->nullable()->after('course_id')->constrained('course_batches')->nullOnDelete();
            $table->timestamp('left_at')->nullable()->after('completed_at');
            $table->decimal('final_score', 5, 2)->nullable()->after('left_at');
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('course_batch_id');
            $table->dropColumn(['left_at', 'final_score']);
        });
    }
};
