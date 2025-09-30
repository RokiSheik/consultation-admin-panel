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
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // e.g. "course-101"
            // Section 1
            $table->string('section1_image')->nullable();
            $table->string('section1_title')->nullable();
            $table->json('section1_bullets')->nullable(); // array
            $table->decimal('section1_regular_price', 10, 2)->nullable();
            $table->decimal('section1_offer_price', 10, 2)->nullable();
            $table->string('section1_registration_text')->nullable();

            // Section 2
            $table->longText('section2_description')->nullable(); // supports HTML
            $table->json('section2_class_details')->nullable(); // array of items

            // Section 3
            $table->string('section3_submit_text')->nullable();

            // Section 4
            $table->string('section4_terms_title')->nullable();
            $table->json('section4_terms_bullets')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_pages');
    }
};
