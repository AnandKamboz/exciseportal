<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('district_wards', function (Blueprint $table) {
            $table->id();
            $table->integer('district_id')->nullable();
            $table->string('district_name');
            $table->integer('ward_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('district_wards');
    }
};
