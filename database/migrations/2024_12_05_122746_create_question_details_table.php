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
         * Table: question_details
         * Columns:
         * - question: The text of the question (string, not nullable).
         * - mark: The mark assigned to the question (unsigned integer, default is 1).
         */
        Schema::create('question_details', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->timestamps(); // Created at and updated at timestamps
            $table->longText('question'); // Question text (text field to allow long questions)
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_details');
    }
};
