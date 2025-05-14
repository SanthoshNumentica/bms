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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string("doctor_id")->unique()->nullable();
            $table->unsignedBigInteger("title_fk_id");
            $table->string("name");
            $table->unsignedBigInteger("gender_fk_id");
            $table->unsignedBigInteger("blood_group_fk_id");
            $table->string("mobile_no");
            $table->string("email_id")->unique();
            $table->date("dob")->nullable();
            $table->string("address");
            $table->string("street");
            $table->string("pincode");
            $table->string("city");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
