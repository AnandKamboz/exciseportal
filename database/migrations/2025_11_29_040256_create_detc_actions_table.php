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
        Schema::create('detc_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('complaint_id');
            $table->enum('proposed_action', ['actionable', 'non_actionable']);
            $table->string('action_taken')->nullable();
            $table->string('reason')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('detc_user_id');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detc_actions');
    }
};
