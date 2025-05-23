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
        Schema::create('whatsapp_log', function (Blueprint $table) {
            $table->id();
            $table->string('from');
            $table->string('to');
            $table->string('message_type')->default('Plain Text');
            $table->string('message');
            $table->enum('status', ['Sent', 'Failed']);
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_log');
    }
};
