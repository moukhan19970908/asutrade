<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('onec_users', function (Blueprint $table) {
            $table->string('firebase_token', 512)->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('onec_users', function (Blueprint $table) {
            $table->dropColumn('firebase_token');
        });
    }
};
