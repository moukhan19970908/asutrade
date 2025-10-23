<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

use Illuminate\Database\Eloquent\Relations\HasMany;
use MoonShine\Laravel\Buttons\CreateButton;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\AlpineJs;
use MoonShine\Support\Enums\JsEvent;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Url;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Switcher;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Support\ListOf;


/**
 * @extends ModelResource<Product>
 */
class ProductResource extends ModelResource
{
    protected string $model = Product::class;

    protected string $title = 'Товары';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name')->sortable(),
            Select::make('Категория', 'category_id')
                ->options(fn() => \App\Models\Category::pluck('name', 'id')->toArray())
                ->sortable(),
            Select::make('Бренд', 'brand_id')
                ->options(fn() => \App\Models\Brand::pluck('name', 'id')->toArray())
                ->sortable(),
            Number::make('Цена', 'price')->sortable(),
            Switcher::make('В наличии', 'in_stock')->sortable(),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                Text::make('Название', 'name')
                    ->required()
                    ->placeholder('Введите название товара'),
                Select::make('Категория', 'category_id')
                    ->options(fn() => \App\Models\Category::pluck('name', 'id')->toArray())
                    ->required(),
                Select::make('Бренд', 'brand_id')
                    ->options(fn() => \App\Models\Brand::pluck('name', 'id')->toArray())
                    ->searchable(),
                Number::make('Цена', 'price')
                    ->required()
                    ->min(0)
                    ->step(0.01),
                Textarea::make('Описание', 'description')
                    ->placeholder('Введите описание товара'),
                Image::make('Изображение', 'image')
                    ->disk('public')
                    ->dir('products'),
                Switcher::make('В наличии', 'in_stock')
                    ->default(true),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Название', 'name'),
            Select::make('Категория', 'category_id')
                ->options(fn() => \App\Models\Category::pluck('name', 'id')->toArray()),
            Select::make('Бренд', 'brand_id')
                ->options(fn() => \App\Models\Brand::pluck('name', 'id')->toArray()),
            Number::make('Цена', 'price'),
            Textarea::make('Описание', 'description'),
            Image::make('Изображение', 'image'),
            Switcher::make('В наличии', 'in_stock'),
            HasMany::make('Остатки на складах', 'warehouses', new class extends \MoonShine\Laravel\Resources\ModelResource {
                public function fields(): array {
                    return [
                        \MoonShine\UI\Fields\Text::make('Склад', 'name'),
                        \MoonShine\UI\Fields\Number::make('Остаток', 'pivot.stock'),
                    ];
                }
            }),
        ];
    }

    /**
     * @return array
     */
    public function resourceActions(): array
    {
        return [
            Url::make('/','dsa'),
        ];
    }

    protected function topButtons(): ListOf
    {
        return parent::topButtons()
            ->add(
                ActionButton::make('Импорт товаров', route('admin.products.import.form'))
                    ->icon('pencil')
                    ->primary()
            );
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return parent::getTitle();
    }

    /**
     * @param Product $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:51200'],
            'in_stock' => ['boolean'],
        ];
    }
}
