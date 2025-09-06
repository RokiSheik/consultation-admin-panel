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
        Schema::create('consulting_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email_address')->unique();
            $table->string('phone_number');
            $table->string('whatsapp_number');
            $table->string('company_name')->nullable();
            $table->string('website_social_media')->nullable();
            $table->string('city_country');
            $table->integer('age');
            $table->text('how_heard_about_us'); // Stores comma-separated values
            $table->text('how_heard_about_us_other')->nullable(); // For 'Other' specification
            $table->text('applying_for'); // Stores comma-separated values
            $table->text('applying_for_other')->nullable(); // For 'Other' specification
            $table->text('best_describes_you'); // Stores comma-separated values
            $table->text('best_describes_you_other')->nullable(); // For 'Other' specification
            $table->text('current_business_description');
            $table->string('current_monthly_revenue');
            $table->string('business_duration');
            $table->text('priority_next_months'); // Stores comma-separated values
            $table->text('priority_next_months_other')->nullable(); // For 'Other' specification
            $table->text('biggest_challenges'); // Stores comma-separated values
            $table->text('biggest_challenges_other')->nullable(); // For 'Other' specification
            $table->text('stopped_fixing_challenges');
            $table->text('specific_outcome'); // Stores comma-separated values
            $table->text('specific_outcome_other')->nullable(); // For 'Other' specification
            $table->string('commitment_level');
            $table->string('willing_to_invest');
            $table->string('investment_budget');
            $table->string('book_discovery_call');
            $table->text('specific_request')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consulting_requests');
    }
};
