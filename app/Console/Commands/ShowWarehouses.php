<?php

namespace App\Console\Commands;

use App\Models\Warehouse;
use App\Support\CityCatalog;
use Illuminate\Console\Command;

/**
 * Сверка складов 1С с городами из config/stores.php и предпросмотр
 * городских страниц каталога.
 *
 * Городская страница работает только если для города найден склад —
 * иначе она повторяла бы соседний город. Город может быть намеренно
 * оставлен без склада (пустой список в конфиге): это не ошибка,
 * а решение не плодить дубли.
 */
class ShowWarehouses extends Command
{
    protected $signature = 'stores:warehouses {--pages : Показать, какие городские страницы получатся}';

    protected $description = 'Склады, их привязка к городам и предпросмотр городских страниц';

    public function handle(): int
    {
        $this->showWarehouses();
        $misconfigured = $this->showMapping();

        if ($this->option('pages')) {
            $this->showPages();
        }

        if ($misconfigured !== []) {
            $this->newLine();
            $this->error('Склад не найден: '.implode(', ', $misconfigured).'.');
            $this->line('Городские страницы для них отдают 404 — это защита от дублей.');
            $this->line('Впишите точное имя склада в config/stores.php → cities.<город>.warehouses');
            $this->line('или оставьте пустой список, если городских страниц быть не должно.');

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    protected function showWarehouses(): void
    {
        $warehouses = Warehouse::orderBy('id')->get();

        $this->info('Склады в базе:');

        if ($warehouses->isEmpty()) {
            $this->warn('  складов нет');

            return;
        }

        foreach ($warehouses as $warehouse) {
            $this->line(sprintf('  #%-3s %s', $warehouse->id, $warehouse->name));
        }
    }

    /**
     * @return array<string> города, где склад указан, но не найден
     */
    protected function showMapping(): array
    {
        $this->newLine();
        $this->info('Привязка городов:');

        $rows = [];
        $misconfigured = [];

        foreach (config('stores.cities') as $slug => $city) {
            $expected = (array) ($city['warehouses'] ?? []);
            $ids = CityCatalog::warehouseIds($slug);

            $status = match (true) {
                $ids !== [] => 'склады '.implode(', ', $ids),
                $expected === [] => 'без каталога (намеренно)',
                default => 'СКЛАД НЕ НАЙДЕН',
            };

            if ($ids === [] && $expected !== []) {
                $misconfigured[] = $slug;
            }

            $rows[] = [
                $city['name'],
                $slug,
                $expected !== [] ? implode(', ', $expected) : '—',
                $status,
                $ids !== [] ? 'да' : 'нет',
            ];
        }

        $this->table(['Город', 'Слаг', 'Ожидается склад', 'Состояние', 'Страницы каталога'], $rows);

        return $misconfigured;
    }

    /**
     * Предпросмотр: какие страницы получатся и какие уйдут под noindex.
     */
    protected function showPages(): void
    {
        $this->newLine();
        $this->info('Городские страницы каталога (порог индексации — '.CityCatalog::MIN_PRODUCTS.' позиции):');

        $indexable = 0;
        $thin = 0;

        foreach (config('stores.cities') as $slug => $city) {
            if (! CityCatalog::configured($slug)) {
                continue;
            }

            $this->newLine();
            $this->line('<comment>'.$city['name'].'</comment>');

            $rows = CityCatalog::categoriesWithStock($slug);

            if ($rows->isEmpty()) {
                $this->line('  остатков нет ни в одной категории');

                continue;
            }

            foreach ($rows as $row) {
                $ok = $row['count'] >= CityCatalog::MIN_PRODUCTS;
                $ok ? $indexable++ : $thin++;

                $this->line(sprintf(
                    '  %s /catalog/%s/%s — %d поз.%s',
                    $ok ? '<info>✓</info>' : '<comment>·</comment>',
                    $row['category']->slug,
                    $slug,
                    $row['count'],
                    $ok ? '' : ' (noindex, не в карте сайта)',
                ));
            }
        }

        $this->newLine();
        $this->line("Итого: <info>{$indexable}</info> страниц в индекс, {$thin} под noindex.");
    }
}
