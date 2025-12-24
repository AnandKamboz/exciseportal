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
        Schema::create('eto_deletion_logs', function (Blueprint $table) {
            $table->id();
             // HQ (Admin) who deleted
            $table->unsignedBigInteger('hq_user_id');
            $table->unsignedBigInteger('hq_mobile');

            // ETO details (snapshot at delete time)
            $table->unsignedBigInteger('eto_user_id');
            $table->unsignedBigInteger('eto_district_id');
            $table->unsignedBigInteger('eto_ward_number');
            $table->unsignedBigInteger('eto_mobile');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eto_deletion_logs');
    }
};
