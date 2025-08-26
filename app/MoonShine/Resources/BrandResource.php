<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\HasMany;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;

/**
 * @extends ModelResource<Brand>
 */
class BrandResource extends ModelResource
{
    protected string $model = Brand::class;

    protected string $title = 'Бренды';
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name')->sortable(),
            Image::make('Логотип', 'logo')->disk('public'),
            Switcher::make('Популярный', 'is_popular')->sortable(),
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
                    ->placeholder('Введите название бренда'),
                Textarea::make('Описание', 'description')
                    ->placeholder('Введите описание бренда'),
                Image::make('Логотип', 'logo')
                    ->disk('public')
                    ->dir('brands'),
                Switcher::make('Популярный', 'is_popular')
                    ->default(false),
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
            Textarea::make('Описание', 'description'),
            Image::make('Логотип', 'logo')->disk('public'),
            Switcher::make('Популярный', 'is_popular'),
            HasMany::make('Товары', 'products', new ProductResource()),
        ];
    }

    /**
     * @param Brand $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'is_popular' => ['boolean'],
        ];
    }
}
