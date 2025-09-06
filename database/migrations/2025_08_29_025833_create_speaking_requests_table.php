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
        Schema::create('speaking_requests', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->text('business_description');
            $table->string('speaker_request'); // Assuming this is a single speaker's name
            $table->date('speaking_date');
            $table->text('event_time');
            $table->string('speaking_location');
            $table->string('speaking_length');
            $table->text('talking_points');
            $table->string('keynote_speaker');
            $table->text('audience_size');
            $table->string('advertise_event');
            $table->text('social_media_links');
            $table->json('event_content_distribution')->nullable(); // Use JSON for the multiple checkboxes
            $table->string('hotel_flights_agreement');
            $table->string('credentials_agreement');
            $table->string('powerpoint_access');
            $table->string('raw_footage_agreement');
            $table->string('media_presence_agreement');
            $table->string('content_approval_agreement');
            $table->text('other_engagements');
            $table->string('fee_understanding');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speaking_requests');
    }
};
