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
        Schema::create('case_law_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('constitution_node_id')->constrained()->onDelete('cascade');
            $table->string('case_title');
            $table->string('citation');
            $table->text('relevance_summary')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_law_references');
    }
};
