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
        Schema::create('detc_delete_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('detc_user_id');
            $table->string('detc_name')->nullable();
            $table->string('detc_mobile', 10)->nullable();

            $table->unsignedBigInteger('district_id')->nullable();
            $table->string('district_name')->nullable();

            $table->unsignedBigInteger('deleted_by'); 

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
        Schema::dropIfExists('detc_delete_logs');
    }
};
