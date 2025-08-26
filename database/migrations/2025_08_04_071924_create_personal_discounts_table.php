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
        Schema::create('personal_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('discount_percent', 5, 2)->comment('Процент скидки');
            $table->boolean('is_active')->default(true)->comment('Активна ли скидка');
            $table->text('description')->nullable()->comment('Описание скидки');
            $table->timestamps();
            
            // Уникальный индекс для предотвращения дублирования скидок
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_discounts');
    }
};
