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
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('motor_id')->constrained('motors')->onDelete('cascade');
            $table->float('latitude', 10, 6);
            $table->float('longitude', 10, 6);                        
            $table->float('distance', 10, 6); 
            $table->float('total_distance', 10, 6);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trackings');
    }
};