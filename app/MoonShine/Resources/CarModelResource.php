<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\CarModel;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;

/**
 * @extends ModelResource<CarModel>
 */
class CarModelResource extends ModelResource
{
    protected string $model = CarModel::class;

    protected string $title = 'Модели';

    protected string $column = 'name';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name')->sortable(),
            BelongsTo::make('Марка', 'mark', 'name', CarMarkResource::class)->sortable(),
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
                    ->placeholder('Введите название модели'),
                BelongsTo::make('Марка', 'mark', 'name', CarMarkResource::class)
                    ->required()
                    ->searchable()
                    ->placeholder('Выберите марку'),
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
            BelongsTo::make('Марка', 'mark', 'name', CarMarkResource::class),
        ];
    }

    /**
     * @param CarModel $item
     *
     * @return array<string, string[]|string>
     */
    protected function rules(mixed $item): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'mark_id' => ['required', 'integer', 'exists:car_marks,id'],
        ];
    }

    /**
     * @return list<string>
     */
    protected function search(): array
    {
        return ['id', 'name'];
    }
}
