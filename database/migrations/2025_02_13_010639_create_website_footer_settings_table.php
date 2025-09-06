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
        Schema::create('website_footer_settings', function (Blueprint $table) {
            $table->id();

            $table->string('footer_logo')->nullable();
            $table->text('about_description')->nullable();
            $table->string('playstore_link')->nullable();
            $table->string('applestore_link')->nullable();

            $table->text('office_address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->json('footer_links')->nullable();

            $table->text('copyright_text')->nullable();
            $table->json('social_links')->nullable();
            $table->string('payment_method_image')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_footer_settings');
    }
};
