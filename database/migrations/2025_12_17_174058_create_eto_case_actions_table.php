<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eto_case_actions', function (Blueprint $table) {
            $table->id();
            $table->string('application_id')->index();
            $table->uuid('secure_id')->index();
            $table->unsignedBigInteger('action_by')->nullable();
            $table->string('role')->default('ETO');
            $table->string('eto_ward')->nullable();
            $table->string('eto_district')->nullable();
            $table->string('proposed_action')->nullable();
            $table->string('action_status')->nullable();
            $table->string('reason')->nullable();
            $table->string('missing_info')->nullable();
            $table->string('report_file')->nullable();
            $table->text('remarks')->nullable();
            $table->string('action')->nullable();
            $table->string('status')->nullable();
            $table->string('current_status')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('current_level')->nullable();
            // ETO | HQ | DETC | JC | CLOSED

            $table->string('forwarded_to')->nullable();
            // HQ | DETC | JC

            $table->timestamp('action_taken_at')->nullable();
            // actual business action time

            $table->boolean('is_final')->default(0);
            // 1 = case finally closed

            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eto_case_actions');
    }
};
