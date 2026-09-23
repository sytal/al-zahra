<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('research_papers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->json('title');
            $table->string('slug')->unique();
            $table->json('research_question')->nullable();
            $table->json('methodology_summary')->nullable();
            $table->json('findings_summary')->nullable();
            $table->json('significance')->nullable();
            $table->string('full_paper_type');
            $table->string('external_url')->nullable();
            $table->year('published_year')->nullable();
            $table->json('co_authors')->nullable();
            $table->boolean('is_published')->default(false);
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_papers');
    }
};
