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
        Schema::create('user_records', function (Blueprint $table) {
            $table->unsignedInteger('record_id');
            $table->unsignedInteger("user_id");
            $table->primary(['record_id', 'user_id']);
            $table->foreign("user_id")
                ->references("id")
                ->on("student_admissions")
                ->onDelete('cascade');
            $table->string('status', 255)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->date('due_date')->nullable();
            $table->string('late_fee', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_records');
    }
};
