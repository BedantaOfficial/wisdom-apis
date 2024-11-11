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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID for the attendance record
            $table->unsignedInteger("student_id");
            $table->foreign("student_id")
                ->references("id")
                ->on("student_admissions")
                ->onDelete('cascade');
            $table->date('date'); // Date of the attendance
            $table->enum('status', ['present', 'absent']); // Status, either 'present' or 'absent'
            $table->timestamps(); // Created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
