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
            // $table->string('secure_id');

            // $table->string('complainant_name')->nullable();
            // $table->string('complainant_phone');
            // $table->string('complainant_email')->nullable();
            // $table->string('complainant_aadhaar', 12)->nullable();
            // $table->text('complainant_address')->nullable();
            // $table->string('upload_document')->nullable();
            // $table->enum('complaint_type', ['vat', 'gst', 'excise'])->nullable();

            // $table->string('pin_code')->nullable();
            // $table->string('complainant_state')->nullable();
            // $table->string('complainant_district')->nullable();
            // $table->string('bank_account')->nullable();
            // $table->string('confirm_bank_account')->nullable();
            // $table->string('bank_name')->nullable();
            // $table->string('ifsc_code')->nullable();
            // $table->text('bank_branch_address')->nullable();


            // $table->string('firm_name')->nullable();
            // $table->string('gstin')->nullable();
            // $table->text('firm_address')->nullable();
            // $table->decimal('estimate_tax_amount', 15, 2)->nullable();


            // $table->string('proof_document')->nullable();
            // $table->text('remarks')->nullable();


            // $table->boolean('is_fraud_related')->default(false);



            // $table->string('complaint_id')->unique();
            // $table->integer('against_district_id')->nullable();
            // $table->string('detc_status')->nullable();
            // $table->string('detc_remarks')->nullable();
            // $table->boolean('detc_updated_flag')->default(0)->comment('0 = Not Updated, 1 = Updated');
            // $table->boolean('is_completed')->default(false);
            // $table->timestamps();

                $table->string('secure_id', 64)->unique(); // random secure ID (e.g., Str::uuid() ya Str::random(32))
                $table->string('application_id', 50)->unique()->nullable(); // complaint application number

                // 🔹 Step 1 — Informer Details
                $table->string('complainant_name');
                $table->string('complainant_phone', 10);
                $table->string('complainant_email')->nullable();
                $table->string('complainant_aadhar', 12);
                $table->text('complainant_address');

                // 🔹 Step 2 — Tax Information
                $table->enum('complaint_type', ['gst', 'excise', 'vat']);

                // 🔹 Step 3 — GST-related Fields
                $table->string('gst_firm_name')->nullable();
                $table->string('gst_gstin', 15)->nullable();
                $table->text('gst_firm_address')->nullable();
                $table->string('gst_proof')->nullable(); // file path

                // 🔹 Step 3 — VAT-related Fields
                $table->string('vat_firm_name')->nullable();
                $table->string('vat_tin')->nullable();
                $table->text('vat_firm_address')->nullable();
                $table->string('vat_proof')->nullable(); // file path

                // 🔹 Step 3 — Excise-related Fields
                $table->string('excise_name')->nullable();
                $table->string('excise_desc')->nullable();
                $table->string('excise_place')->nullable();
                $table->string('excise_time')->nullable();
                $table->text('excise_details')->nullable();
                $table->string('excise_vehicle_number', 15)->nullable();
                $table->string('excise_proof')->nullable(); // file path

                $table->string('gst_locality')->nullable();
                $table->string('district')->nullable();
                $table->text('gst_description')->nullable();
                $table->string('gst_vehicle_number', 15)->nullable(); 

                // 🔹 Step 3 — VAT Additional Fields
                $table->string('vat_locality')->nullable();
                // $table->string('vat_district')->nullable();
                $table->text('vat_description')->nullable();
                $table->string('vat_vehicle_number', 15)->nullable(); 

                // 🔹 System Fields
                $table->unsignedBigInteger('user_id')->nullable()->comment('Linked user ID if logged in');
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
