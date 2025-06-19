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
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('payment_id')->unique();
            $table->string('invoice_id')->unique();

            // Both will delete related payment records if the referenced row is deleted (cascade)
            $table->foreignId('patient_fk_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

            $table->date('payment_date')->nullable();
            $table->string('description')->nullable();
            $table->decimal('amount', 10, 2)->nullable(); // Allows up to 99999999.99
            $table->timestamps();
            $table->softDeletes(); // Automatically adds deleted_at nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
