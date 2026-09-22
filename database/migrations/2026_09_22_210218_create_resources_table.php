<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->json('title');
            $table->string('slug')->unique();
            $table->json('description');
            $table->string('resource_type');
            $table->boolean('is_free')->default(true);
            $table->decimal('price', 8, 2)->nullable();
            $table->unsignedInteger('download_count')->default(0);
            $table->boolean('is_published')->default(false);
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
