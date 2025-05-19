<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('case_reports', function (Blueprint $table) {
            $table->id();
            $table->string('case_id');
            $table->foreignId('patient_fk_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('doc_ref_fk_id')->constrained('doctors')->onDelete('cascade');
            $table->string('description');
            $table->string('remarks');
            $table->enum('status', ['closed', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_reports');
    }
};
