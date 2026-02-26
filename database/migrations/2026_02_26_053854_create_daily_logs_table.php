<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('log_date');
            $table->text('morning_summary')->nullable();
            $table->text('afternoon_summary')->nullable();
            $table->json('tasks_completed')->nullable();
            $table->enum('mood', ['great', 'good', 'neutral', 'difficult', 'struggling'])->nullable();
            $table->text('blockers')->nullable();
            $table->enum('status', ['submitted', 'reviewed', 'revision_requested'])->default('submitted');
            $table->text('admin_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->unique(['employee_id', 'log_date']);
            $table->index('status');
            $table->index('log_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_logs');
    }
};
