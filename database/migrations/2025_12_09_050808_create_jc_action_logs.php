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
        // Schema::create('jc_action_logs', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
        Schema::create('jc_action_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('complaint_id')->nullable();
            $table->string('application_id')->nullable();

            $table->unsignedBigInteger('jc_id')->nullable();
            $table->string('jc_name')->nullable();
            $table->string('jc_mobile')->nullable();
            $table->string('jc_district')->nullable();

            $table->string('action_type')->nullable();

            $table->unsignedBigInteger('assigned_detc_id')->nullable();
            $table->string('assigned_detc_name')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jc_action_logs');
    }
};
