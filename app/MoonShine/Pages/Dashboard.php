<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Div;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
#[\MoonShine\MenuManager\Attributes\SkipMenu]

class Dashboard extends Page
{
    /**
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    public function getTitle(): string
    {
        return $this->title ?: 'Панель управления';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        return [
            Grid::make([
                Column::make([
                    Div::make([
                        'Категории: ' . Category::count(),
                    ])->class('p-4 bg-success-50 rounded-lg border border-success-200')
                ])->columnSpan(4),
                Column::make([
                    Div::make([
                        'Товары: ' . Product::count(),
                    ])->class('p-4 bg-info-50 rounded-lg border border-info-200')
                ])->columnSpan(4),
                Column::make([
                    Div::make([
                        'Бренды: ' . Brand::count(),
                    ])->class('p-4 bg-warning-50 rounded-lg border border-warning-200')
                ])->columnSpan(4),
            ])
        ];
    }
}
