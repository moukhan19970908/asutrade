<?php

namespace App\Console\Commands;

use App\Models\Warehouse;
use App\Support\CityCatalog;
use Illuminate\Console\Command;

/**
 * Сверка складов 1С с городами из config/stores.php.
 *
 * Городские страницы каталога работают только если для города найден
 * склад — иначе страница была бы копией соседней. Команда показывает,
 * что реально лежит в базе и какие города остались без привязки.
 */
class ShowWarehouses extends Command
{
    protected $signature = 'stores:warehouses';

    protected $description = 'Показать склады и их привязку к городам из config/stores.php';

    public function handle(): int
    {
        $warehouses = Warehouse::orderBy('id')->get();

        $this->info('Склады в базе:');

        if ($warehouses->isEmpty()) {
            $this->warn('  складов нет');
        } else {
            foreach ($warehouses as $warehouse) {
                $this->line(sprintf('  #%-3s %s', $warehouse->id, $warehouse->name));
            }
        }

        $this->newLine();
        $this->info('Привязка городов:');

        $rows = [];

        foreach (config('stores.cities') as $slug => $city) {
            $ids = CityCatalog::warehouseIds($slug);
            $expected = implode(', ', (array) ($city['warehouses'] ?? []));

            $rows[] = [
                $city['name'],
                '/catalog/{категория}/'.$slug,
                $expected !== '' ? $expected : '—',
                $ids !== [] ? implode(', ', $ids) : 'НЕ НАЙДЕН',
            ];
        }

        $this->table(['Город', 'URL', 'Ожидается склад', 'id складов'], $rows);

        $missing = collect(config('stores.cities'))
            ->keys()
            ->reject(fn ($slug) => CityCatalog::configured($slug));

        if ($missing->isNotEmpty()) {
            $this->newLine();
            $this->warn('Без склада: '.$missing->implode(', ').'.');
            $this->warn('Городские страницы для них отдают 404 — это защита от дублей.');
            $this->warn('Впишите точное имя склада в config/stores.php → cities.<город>.warehouses.');

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
