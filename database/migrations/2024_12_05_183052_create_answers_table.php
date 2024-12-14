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
         * Table: answers
         * Columns:
         * - theory: Text answer for theory (long text, optional).
         * - practical: Text answer for practical (long text, optional).
         * - mcq: Text answer for MCQs (long text, optional).
         */
        Schema::create('answers', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->timestamps(); // Created at and updated at timestamps
            $table->string('theory')->nullable(); // Theory answer (optional, no limit)
            $table->string('practical')->nullable(); // Practical answer (optional, no limit)
            $table->string('mcq')->nullable(); // MCQ answer (optional, no limit)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
