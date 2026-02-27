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
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('punch_in_time');
            $table->decimal('punch_in_latitude', 10, 8);
            $table->decimal('punch_in_longitude', 11, 8);
            $table->time('punch_out_time')->nullable();
            $table->decimal('punch_out_latitude', 10, 8)->nullable();
            $table->decimal('punch_out_longitude', 11, 8)->nullable();
            $table->enum('status', ['PUNCHED_IN', 'PUNCHED_OUT'])->default('PUNCHED_IN');
            $table->timestamps();

            $table->unique(['employee_id', 'date']);
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
