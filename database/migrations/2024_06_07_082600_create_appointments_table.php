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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointmentId');
            $table->unsignedBigInteger('vetId');
            $table->unsignedBigInteger('petId');
            $table->dateTime('appointmentDatetime');
            $table->string('status');
            $table->text('prognosis')->nullable();
            $table->timestamps();

            $table->foreign('vetId')->references('userId')->on('users')->onDelete('cascade');
            $table->foreign('petId')->references('petId')->on('pets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
