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
        Schema::create('appointment', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_fk_id')->unsigned()->index();
            $table->integer('doctor_fk_id')->unsigned()->index();
            $table->integer('created_by')->unsigned()->index();
            $table->string('appointment_for')->default(null);
            $table->dateTime('appointment_on')->default(null);
            $table->tinyInteger('isrescheduled')->default(null);
            $table->tinyInteger('isvisited')->default(false);
            $table->string('remarks')->default(null);
            $table->dateTime('deleted_at')->default(null);
            $table->dateTime('to_date')->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment');
    }
};
