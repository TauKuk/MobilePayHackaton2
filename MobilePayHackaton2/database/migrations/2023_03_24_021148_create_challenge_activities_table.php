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
        Schema::create('challenge_activities', function (Blueprint $table) {
            $table->id();

            $table->integer('challengeID');
            $table->string('stravaID');
            $table->double('startingDistance', 8, 2);
            $table->boolean('uploadedToStrava')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_activities');
    }
};
