<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('mark_id')->constrained('car_marks')->cascadeOnDelete();
            $table->timestamps();

            $table->index('mark_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_models');
    }
};
