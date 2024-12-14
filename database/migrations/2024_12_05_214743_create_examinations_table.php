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
        /*
         * Create a table for examinations.
         *
         * Columns:
         * - exam_date: The date of the examination (unique, not nullable).
         * - mcq: Indicates if the exam has MCQ (default true).
         * - theory: Indicates if the exam has theory (default true).
         * - practical: Indicates if the exam has practical (default true).
         * - theory_question_id: Foreign key for theory questions.
         * - mcq_question_id: Foreign key for MCQ questions.
         * - practical_question_id: Foreign key for practical questions.
         * - theory_total_marks: Total marks for the theory section.
         * - mcq_total_marks: Total marks for the MCQ section.
         * - practical_total_marks: Total marks for the practical section.
         * - time_in_seconds: Exam duration in seconds (1 minute to 12 hours).
         */
        Schema::create('examinations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('exam_date')->unique(); // Exam date, unique and not nullable
            $table->boolean('mcq')->default(true); // Has MCQ section
            $table->boolean('theory')->default(true); // Has theory section
            $table->boolean('practical')->default(true); // Has practical section
            $table->unsignedBigInteger('theory_question_id')->nullable(); // FK for theory questions
            $table->unsignedBigInteger('mcq_question_id')->nullable(); // FK for MCQ questions
            $table->unsignedBigInteger('practical_question_id')->nullable(); // FK for practical questions
            $table->unsignedInteger('theory_total_marks')->nullable(); // Total marks for theory
            $table->unsignedInteger('mcq_total_marks')->nullable(); // Total marks for MCQ
            $table->unsignedInteger('practical_total_marks')->nullable(); // Total marks for practical
            $table->unsignedInteger('time_in_seconds'); // Duration in seconds (1 minute to 12 hours)

            // Add foreign key constraints 
            $table->foreign('theory_question_id')->references('id')->on('questions')->onDelete('set null');
            $table->foreign('mcq_question_id')->references('id')->on('questions')->onDelete('set null');
            $table->foreign('practical_question_id')->references('id')->on('questions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examinations');
    }
};
