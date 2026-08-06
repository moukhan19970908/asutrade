<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * cars.user_id теперь ссылается на onec_users (клиенты из 1С),
 * а не на users (пользователи приложения).
 *
 * Старые значения указывают на users.id и в новой таблице смысла не имеют,
 * поэтому обнуляются: связь с клиентом остаётся по cars.phone.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        DB::table('cars')->update(['user_id' => null]);

        Schema::table('cars', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('onec_users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        DB::table('cars')->update(['user_id' => null]);

        Schema::table('cars', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }
};
