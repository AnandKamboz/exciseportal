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
        Schema::create('detc_update_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detc_user_id');
            $table->unsignedBigInteger('updated_by');

            $table->string('old_name')->nullable();
            $table->string('new_name')->nullable();

            $table->string('old_mobile', 10)->nullable();
            $table->string('new_mobile', 10)->nullable();

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
        Schema::dropIfExists('detc_update_logs');
    }
};
