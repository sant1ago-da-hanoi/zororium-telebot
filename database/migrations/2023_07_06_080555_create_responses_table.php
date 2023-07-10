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
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->string('update_id')->unique();
            $table->string('message_id')->unique();
            $table->string('from_id');
            $table->string('from_username')->nullable();
            $table->string('from_language_code');
            $table->boolean('from_is_bot')->default(false);
            $table->string('chat_id');
            $table->string('chat_is_bot')->default(false);
            $table->string('chat_type');
            $table->string('chat_username')->nullable();
            $table->dateTime('date');
            $table->string('text');
            $table->boolean('is_command')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
