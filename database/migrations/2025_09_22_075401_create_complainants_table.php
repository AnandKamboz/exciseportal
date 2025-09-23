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
        Schema::create('complainants', function (Blueprint $table) {
            $table->id();
            $table->string('secure_id');
            $table->string('complainant_name');
            $table->string('mobile');
            $table->string('email')->nullable();
            $table->string('aadhaar')->nullable();
            $table->text('address')->nullable();
            $table->string('upload_document')->nullable();
            $table->enum('complaint_type', ['vat', 'gst', 'excise']);

            // Firm being reported
            $table->string('firm_name')->nullable();
            $table->string('gstin')->nullable();
            $table->text('firm_address')->nullable();

            // Optional document and remarks
            $table->string('proof_document')->nullable();
            $table->text('remarks')->nullable();

            // Fraud/Evasion question (from screen 1)
            $table->boolean('is_fraud_related')->default(false);

            // Confirmation page
            $table->string('complaint_id')->unique();
            $table->boolean('is_completed')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complainants');
    }
};
