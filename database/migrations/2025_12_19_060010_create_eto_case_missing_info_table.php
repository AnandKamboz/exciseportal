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
        Schema::create('eto_case_missing_info', function (Blueprint $table) {
            $table->id();

            // Application
            $table->string('application_id')->index();
            $table->string('complain_secure_id')->index();

            // Applicant
            $table->unsignedBigInteger('applicant_user_id')->index();

            // DETC / ETO details
            $table->unsignedBigInteger('eto_user_id')->nullable()->index();
            $table->string('eto_name')->nullable();
            $table->unsignedBigInteger('eto_district_id')->nullable()->index();
            $table->string('eto_district_name')->nullable();

            // Missing info
            $table->string('missing_key')->index();
            $table->text('eto_remarks')->nullable();
            $table->text('submitted_value');

            // Timeline
            $table->timestamp('eto_marked_at')->nullable();
            $table->timestamp('applicant_submitted_at')->nullable();

            // Audit
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            // Optional foreign keys
            // $table->foreign('applicant_user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('detc_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eto_case_missing_info');
    }
};
