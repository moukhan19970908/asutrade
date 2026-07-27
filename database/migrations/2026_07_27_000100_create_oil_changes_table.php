<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oil_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->cascadeOnDelete();
            $table->string('number')->unique();
            $table->unsignedInteger('mileage');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oil_changes');
    }
};
