<?php

use App\Support\Slugger;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Слаги для категорий, товаров и брендов — читаемые URL вместо id.
 *
 * Бэкфилл идёт чанками и напрямую через DB, минуя Eloquent: на боевой базе
 * товаров десятки тысяч, а модельные события и глобальные скоупы здесь
 * только мешают.
 */
return new class extends Migration
{
    /** Таблицы и префикс для вырожденных имён. */
    protected array $tables = [
        'categories' => 'category',
        'products' => 'tovar',
        'brands' => 'brand',
    ];

    public function up(): void
    {
        foreach (array_keys($this->tables) as $table) {
            if (! Schema::hasColumn($table, 'slug')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->string('slug', 120)->nullable()->after('name');
                });
            }
        }

        foreach ($this->tables as $table => $prefix) {
            $this->backfill($table, $prefix);
        }

        foreach (array_keys($this->tables) as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                $blueprint->unique('slug', $table.'_slug_unique');
            });
        }
    }

    public function down(): void
    {
        foreach (array_keys($this->tables) as $table) {
            if (Schema::hasColumn($table, 'slug')) {
                Schema::table($table, function (Blueprint $blueprint) use ($table) {
                    $blueprint->dropUnique($table.'_slug_unique');
                    $blueprint->dropColumn('slug');
                });
            }
        }
    }

    /**
     * Проставляет слаги всем записям без слага.
     *
     * Занятые слаги держим в памяти: это дешевле, чем проверять каждый
     * запросом, и гарантирует уникальность до наложения unique-индекса.
     */
    protected function backfill(string $table, string $prefix): void
    {
        $taken = DB::table($table)
            ->whereNotNull('slug')
            ->pluck('slug')
            ->flip()
            ->toArray();

        $hasCode = Schema::hasColumn($table, 'code');

        DB::table($table)
            ->whereNull('slug')
            ->orderBy('id')
            ->chunkById(500, function ($rows) use ($table, $prefix, $hasCode, &$taken) {
                foreach ($rows as $row) {
                    $slug = Slugger::safe((string) $row->name, $prefix, $row->id);

                    // Артикул — осмысленный разделитель для одноимённых товаров.
                    if (isset($taken[$slug]) && $hasCode && filled($row->code ?? null)) {
                        $withCode = trim($slug.'-'.Slugger::make((string) $row->code), '-');

                        if ($withCode !== '' && ! isset($taken[$withCode])) {
                            $slug = $withCode;
                        }
                    }

                    if (isset($taken[$slug])) {
                        $attempt = 2;

                        while (isset($taken[$slug.'-'.$attempt])) {
                            $attempt++;
                        }

                        $slug .= '-'.$attempt;
                    }

                    $taken[$slug] = true;

                    DB::table($table)->where('id', $row->id)->update(['slug' => $slug]);
                }
            });
    }
};
