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
        Schema::create('pets', function (Blueprint $table) {
            $table->id('petId');
            $table->unsignedBigInteger('userId');
            $table->string('petImage')->nullable();
            $table->string('name');
            $table->string('breed');
            $table->string('gender');
            $table->string('age');
            $table->text('description')->nullable();
            $table->text('diagnosis')->nullable();
            $table->string('vaccineStatus');
            $table->date('vaccineDate')->nullable();
            $table->enum('adoptionStatus', ['available', 'adopted', 'pending'])->default('available');
            $table->timestamps();

            $table->foreign('userId')->references('userId')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};

