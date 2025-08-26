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
        Schema::table('categories', function (Blueprint $table) {
            // Удаляем старые колонки
            $table->dropColumn(['name_ru', 'name_kz']);
            
            // Добавляем новые колонки
            $table->string('name')->after('id');
            $table->string('image')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Удаляем новые колонки
            $table->dropColumn(['name', 'image']);
            
            // Восстанавливаем старые колонки
            $table->string('name_ru')->after('id');
            $table->string('name_kz')->after('name_ru');
        });
    }
};
