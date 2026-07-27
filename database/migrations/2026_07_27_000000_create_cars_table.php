<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('phone');
            $table->string('name');
            $table->timestamps();

            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
