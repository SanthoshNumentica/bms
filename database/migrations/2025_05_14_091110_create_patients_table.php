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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string("patient_id")->unique()->nullable();
            $table->unsignedBigInteger("title_fk_id");
            $table->string("name");
            $table->string("father_name");
            $table->string("email_id")->unique();
            $table->date("dob")->nullable();
            $table->string("mobile_no");
            $table->string("whatsapp_no");
            $table->unsignedBigInteger("blood_group_fk_id");
            $table->unsignedBigInteger("gender_fk_id");
            $table->string("address");
            $table->string("street");
            $table->string("pincode");
            $table->string("city");
            $table->string( "remarks")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
