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
        Schema::table('examinations', function (Blueprint $table) {
            // Add course_id and semester columns
            $table->unsignedBigInteger('course_id')->after('id');
            $table->unsignedInteger('semester')->after('course_id');

            // Add foreign key constraint for course_id
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('examinations', function (Blueprint $table) {
            // Drop foreign key constraint and columns
            $table->dropForeign(['course_id']);
            $table->dropColumn(['course_id', 'semester']);
        });
    }
};
