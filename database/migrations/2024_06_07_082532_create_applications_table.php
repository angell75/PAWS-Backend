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
        Schema::create('applications', function (Blueprint $table) {
            $table->id('applicationId');
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('petId');
            $table->date('applicationDate');
            $table->date('scheduleDate')->nullable();
            $table->time('scheduleTime')->nullable();
            $table->string('scheduleLocation')->nullable();
            $table->string('status');
            $table->timestamps();

            $table->foreign('userId')->references('userId')->on('users')->onDelete('cascade');
            $table->foreign('petId')->references('petId')->on('pets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
