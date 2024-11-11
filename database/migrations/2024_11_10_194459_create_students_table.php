<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('student_admissions', function (Blueprint $table) {
            $table->unsignedInteger("id")->autoIncrement();
            $table->string('filename', 255);
            $table->string('name', 100);
            $table->string('course', 100);
            $table->date('date_of_admission');
            $table->string('guardian_name', 100);
            $table->bigInteger('guardian_phone');
            $table->bigInteger('mobile_no');
            $table->string('email', 100);
            $table->string('enrollment_no', 50);
            $table->string('reference', 255)->nullable();
            $table->text('address');
            $table->decimal('admission_fees', 10, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->string('duration', 100)->nullable();
            $table->string('paid_total', 1000)->nullable();
            $table->string('status', 255)->nullable();
            $table->string('password', 100)->nullable();
            $table->string('fee_status', 10)->nullable();
            $table->string('late_fee', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_admissions');
    }
};
