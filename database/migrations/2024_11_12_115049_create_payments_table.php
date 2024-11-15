<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->date('updated_at')->nullable();
            $table->string('late_fee', 100)->nullable();
        });

        // Create the trigger to update 'updated_at' before an update on 'user_records'
        DB::statement("
            CREATE TRIGGER update_timestamp_on_status_change
            BEFORE UPDATE ON user_records
            FOR EACH ROW
            BEGIN
                IF NEW.status = 'paid' THEN
                    SET NEW.updated_at = CURRENT_TIMESTAMP;
                ELSE
                    SET NEW.updated_at = NULL;
                END IF;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the trigger if it exists
        DB::statement('DROP TRIGGER IF EXISTS update_user_records_updated_at');
        Schema::dropIfExists('user_records');
    }
};
