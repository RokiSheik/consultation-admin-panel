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
        Schema::create('subscription_packages', function (Blueprint $table) {
            $table->id('package_id'); // Primary key for subscription_packages
            $table->unsignedBigInteger('service_id'); // Match the type of `service_id` in `services`
            $table->string('package_name');
            $table->decimal('price', 10, 2);
            $table->integer('duration'); // Duration in days/months
            $table->timestamps();

            // Define the foreign key constraint
            $table->foreign('service_id')->references('service_id')->on('services')->onDelete('cascade');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_packages');
    }
};
