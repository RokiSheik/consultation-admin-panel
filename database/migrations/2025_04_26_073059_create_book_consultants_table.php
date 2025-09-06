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
        Schema::create('book_consultants', function (Blueprint $table) {
            $table->id();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('company');
        $table->string('title');
        $table->string('email');
        $table->string('phone');
        $table->string('interested_speaker')->nullable();
        $table->string('event_name')->nullable();
        $table->date('event_date')->nullable();
        $table->string('event_location');
        $table->string('event_budget')->nullable();
        $table->string('event_website')->nullable();
        $table->text('additional_info')->nullable();
        $table->boolean('receive_updates')->default(false);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_consultants');
    }
};
