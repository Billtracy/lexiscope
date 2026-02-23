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
        Schema::create('constitution_nodes', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('section'); // chapter, section, subsection
            $table->foreignId('parent_id')->nullable()->constrained('constitution_nodes')->onDelete('cascade');
            $table->string('chapter_number')->nullable();
            $table->string('chapter_title')->nullable();
            $table->string('section_number')->nullable();
            $table->string('section_title')->nullable();
            $table->string('subsection_number')->nullable();
            $table->text('legal_text')->nullable();
            $table->text('plain_english')->nullable();
            $table->json('keywords')->nullable();
            $table->string('status')->default('ai_generated'); // ai_generated, draft, published
            $table->foreignId('locked_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('constitution_nodes');
    }
};
