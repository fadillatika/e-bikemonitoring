<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('batteries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('motor_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('percentage');
            $table->unsignedSmallInteger('kilometers');
            $table->unsignedSmallInteger('kW');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batteries');
    }
};
