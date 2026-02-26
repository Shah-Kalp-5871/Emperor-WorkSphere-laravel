<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->enum('event_type', ['holiday', 'office_off', 'meeting', 'deadline', 'announcement']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('is_all_day')->default(true);
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_rule', 255)->nullable();
            $table->string('color', 7)->nullable();
            $table->enum('visible_to', ['all', 'admin_only'])->default('all');
            $table->foreignId('created_by')->constrained('admins')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('start_date');
            $table->index('event_type');
            $table->index('visible_to');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
