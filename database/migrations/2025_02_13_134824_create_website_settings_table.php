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
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->string('website_name')->nullable();
            $table->string('site_motto')->nullable();
            $table->string('site_icon')->nullable();
            $table->string('website_base_color')->nullable();
            $table->string('website_base_hover_color')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable()->change();
            $table->string('meta_image')->nullable();
            $table->text('cookies_content')->nullable();
            $table->boolean('show_cookies_agreement')->default(false);
            $table->boolean('show_website_popup')->default(false);
            $table->text('popup_content')->nullable();
            $table->text('header_custom_script')->nullable();
            $table->text('footer_custom_script')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->string('meta_keywords')->nullable()->change();
        });
    }
};
