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
        // Schema::create('detc_actions', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('secure_id')->unique();
        //     $table->unsignedBigInteger('complaint_id');
        //     $table->string('detc_district');
        //     $table->string('user_application_id');
        //     $table->enum('proposed_action', ['actionable', 'non_actionable']);
        //     $table->integer('ward_no')->nullable();
        //     $table->string('reason')->nullable();
        //     $table->text('remarks')->nullable();
        //     $table->string('file_name')->nullable();
        //     $table->unsignedBigInteger('detc_user_id');
        //     $table->boolean('is_approved')->default(true)->comment('1 = Approved, 0 = Rejected');
        //     $table->timestamps();
        // });

        Schema::create('detc_actions', function (Blueprint $table) {
            $table->id();
            $table->string('secure_id')->unique();
            $table->unsignedBigInteger('complaint_id');
            $table->string('detc_district');
            $table->string('user_application_id');

            // UPDATED ENUM
            $table->enum('proposed_action', [
                'forward_to_eto',
                'uploaded_report',
                'non_actionable',
            ]);

            $table->integer('ward_no')->nullable();
            $table->string('reason')->nullable(); // false_information, info_incomplete, any_other
            $table->string('missing_info')->nullable(); // gst_number, firm_location, address
            $table->text('remarks')->nullable();
            $table->string('file_name')->nullable();

            $table->string('button_action')->nullable(); // send_to_hq, submit, reject, send_back_to_applicant

            $table->unsignedBigInteger('detc_user_id');
            $table->string('send_to')->nullable()->comment('hq, eto, applicant');
            $table->timestamp('returned_to_detc_at')->nullable();
           $table->timestamp('applicant_submitted_at')->nullable();
            $table->boolean('is_approved')->default(true);
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
