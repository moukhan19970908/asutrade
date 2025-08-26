<?php

namespace App\MoonShine\Resources;

use App\Models\PersonalDiscount;
use MoonShine\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Textarea;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\Laravel\Resources\ModelResource;

class PersonalDiscountResource extends ModelResource
{
    protected string $model = PersonalDiscount::class;

    public function title(): string
    {
        return 'Персональные скидки';
    }

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Select::make('Пользователь', 'user_id')
                ->options(fn() => \App\Models\User::pluck('name', 'id')->toArray())
                ->required()
                ->searchable(),
            Select::make('Товар', 'product_id')
                ->options(fn() => \App\Models\Product::pluck('name', 'id')->toArray())
                ->required()
                ->searchable(),
            Number::make('Процент скидки', 'discount_percent')
                ->min(0)
                ->max(100)
                ->step(0.01)
                ->required(),
            Switcher::make('Активна', 'is_active')
                ->default(true),
            Textarea::make('Описание', 'description'),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                Select::make('Пользователь', 'user_id')
                    ->options(fn() => \App\Models\User::pluck('name', 'id')->toArray())
                    ->required()
                    ->searchable(),
                Select::make('Товар', 'product_id')
                    ->options(fn() => \App\Models\Product::pluck('name', 'id')->toArray())
                    ->required()
                    ->searchable(),
                Number::make('Процент скидки', 'discount_percent')
                    ->min(0)
                    ->max(100)
                    ->step(0.01)
                    ->required(),
                Switcher::make('Активна', 'is_active')
                    ->default(true),
                Textarea::make('Описание', 'description'),
            ])
        ];
    }

    protected function indexFields(): iterable
    {
        return [
            \MoonShine\UI\Fields\ID::make()->sortable(),
            Select::make('Пользователь', 'user_id')
                ->options(fn() => \App\Models\User::pluck('name', 'id')->toArray())
                ->sortable(),
            Select::make('Товар', 'product_id')
                ->options(fn() => \App\Models\Product::pluck('name', 'id')->toArray())
                ->sortable(),
            Number::make('Процент скидки', 'discount_percent')
                ->sortable(),
            Switcher::make('Активна', 'is_active')
                ->sortable(),
        ];
    }
} 