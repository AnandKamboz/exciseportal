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
        Schema::create('hq_creation_logs', function (Blueprint $table) {
            $table->id();
             // HQ who created another HQ
            $table->unsignedBigInteger('created_by_hq_id');

            // HQ which is created
            $table->unsignedBigInteger('created_hq_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hq_creation_logs');
    }
};
