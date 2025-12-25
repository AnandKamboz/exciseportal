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
        Schema::create('detc_transfer_logs', function (Blueprint $table) {
            $table->id();
                $table->unsignedBigInteger('from_detc_id');
                $table->string('from_detc_name')->nullable();
                $table->unsignedBigInteger('from_district_id')->nullable();
                $table->string('from_district_name')->nullable();

                // TO DETC
                $table->unsignedBigInteger('to_detc_id');
                $table->string('to_detc_name')->nullable();
                $table->unsignedBigInteger('to_district_id')->nullable();
                $table->string('to_district_name')->nullable();

                // META
                $table->unsignedBigInteger('transferred_by'); // HQ user id
                $table->text('remarks')->nullable();

                $table->ipAddress('ip_address')->nullable();
                $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detc_transfer_logs');
    }
};
