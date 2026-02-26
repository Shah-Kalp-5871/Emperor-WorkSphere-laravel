<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anonymous_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('anonymous_chat_sessions')->cascadeOnDelete();
            $table->text('message');
            $table->boolean('is_admin_reply')->default(false);
            $table->string('admin_alias', 50)->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index('session_id');
            $table->index('created_at');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anonymous_chat_messages');
    }
};
