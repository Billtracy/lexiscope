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
        Schema::table('constitution_nodes', function (Blueprint $table) {
            $table->float('chapter_sort')->nullable()->after('chapter_number')->index();
            $table->float('section_sort')->nullable()->after('section_number')->index();
            $table->float('subsection_sort')->nullable()->after('subsection_number')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('constitution_nodes', function (Blueprint $table) {
            $table->dropColumn(['chapter_sort', 'section_sort', 'subsection_sort']);
        });
    }
};
