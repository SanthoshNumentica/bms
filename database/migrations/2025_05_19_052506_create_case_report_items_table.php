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
    Schema::create('case_report_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('case_report_id')->constrained()->onDelete('cascade');
        $table->foreignId('scan_type_id')->constrained()->onDelete('cascade');
        $table->foreignId('scan_id')->constrained()->onDelete('cascade');
        $table->json('documents')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_report_items');
    }
};
