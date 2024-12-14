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
        /**
         * Columns:
         * - examination_id: Foreign key referencing the examinations table.
         * - student_id: Foreign key referencing the students table.
         * - theory_answer_id, practical_answer_id, mcq_answer_id: Foreign keys referencing the answers table.
         * - theory_marks_obtained, practical_marks_obtained, mcq_marks_obtained: Marks obtained for each section.
         */
        Schema::create('examination_students', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Foreign keys for relationships
            $table->unsignedBigInteger('examination_id');
            $table->unsignedInteger('student_id');
            $table->unsignedBigInteger('theory_answer_id')->nullable();
            $table->unsignedBigInteger('practical_answer_id')->nullable();
            $table->unsignedBigInteger('mcq_answer_id')->nullable();

            // Marks obtained
            $table->unsignedInteger('theory_marks_obtained')->nullable();
            $table->unsignedInteger('practical_marks_obtained')->nullable();
            $table->unsignedInteger('mcq_marks_obtained')->nullable();

            // Foreign key constraints
            $table
                ->foreign('examination_id')
                ->references('id')
                ->on('examinations')
                ->onDelete('cascade'); // Cascade delete

            $table
                ->foreign('student_id')
                ->references('id')
                ->on('student_admissions')
                ->onDelete('cascade'); // Cascade delete

            $table
                ->foreign('theory_answer_id')
                ->references('id')
                ->on('answers')
                ->onDelete('set null'); // Set null on delete

            $table
                ->foreign('practical_answer_id')
                ->references('id')
                ->on('answers')
                ->onDelete('set null'); // Set null on delete

            $table
                ->foreign('mcq_answer_id')
                ->references('id')
                ->on('answers')
                ->onDelete('set null'); // Set null on delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examination_students');
    }
};
