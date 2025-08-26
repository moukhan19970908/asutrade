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
        Schema::table('products', function (Blueprint $table) {
            // Удаляем старые колонки
            $table->dropColumn(['name_ru', 'name_kz', 'description_ru', 'description_kz']);
            
            // Добавляем новые колонки
            $table->string('name')->after('category_id');
            $table->text('description')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Удаляем новые колонки
            $table->dropColumn(['name', 'description']);
            
            // Восстанавливаем старые колонки
            $table->string('name_ru')->after('category_id');
            $table->string('name_kz')->after('name_ru');
            $table->text('description_ru')->nullable()->after('name_kz');
            $table->text('description_kz')->nullable()->after('description_ru');
        });
    }
};
