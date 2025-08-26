<?php

namespace App\MoonShine\Resources;

use App\Models\Order;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Textarea;
use MoonShine\UI\Fields\Date;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\UI\Components\Layout\Box;

class OrderResource extends ModelResource
{
    protected string $model = Order::class;

    protected string $title = 'Заказы';

    protected string $column = 'order_number';

    public function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Номер заказа', 'order_number')->sortable(),
            BelongsTo::make('Пользователь', 'user', 'name')->sortable(),
            Select::make('Статус', 'status')
                ->options([
                    'pending' => 'Ожидает обработки',
                    'processing' => 'В обработке',
                    'shipped' => 'Отправлен',
                    'delivered' => 'Доставлен',
                    'cancelled' => 'Отменен',
                ])
                ->sortable(),
            Number::make('Сумма', 'total_amount')->sortable(),
            Number::make('Количество товаров', 'items_count')->readonly(),
            Date::make('Дата создания', 'created_at')->sortable(),
        ];
    }

    public function formFields(): iterable
    {
        return [
            Box::make([
                Text::make('Номер заказа', 'order_number')->readonly(),
                BelongsTo::make('Пользователь', 'user', 'name')->readonly(),
                Select::make('Статус', 'status')
                    ->options([
                        'pending' => 'Ожидает обработки',
                        'processing' => 'В обработке',
                        'shipped' => 'Отправлен',
                        'delivered' => 'Доставлен',
                        'cancelled' => 'Отменен',
                    ]),
                Number::make('Сумма заказа', 'total_amount')->readonly(),
                Date::make('Дата создания', 'created_at')->readonly(),
                Textarea::make('Комментарий', 'notes'),
            ]),
            
            Box::make('Информация о доставке', [
                Text::make('Имя получателя', 'shipping_name'),
                Text::make('Телефон', 'shipping_phone'),
                // Text::make('Страна', 'shipping_country'),
                // Text::make('Город', 'shipping_city'),
                // Textarea::make('Адрес', 'shipping_address'),
                // Text::make('Почтовый индекс', 'shipping_postal_code'),
            ]),
        ];
    }

    public function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Номер заказа', 'order_number')->sortable(),
            BelongsTo::make('Пользователь', 'user', 'name')->sortable(),
            Select::make('Статус', 'status')
                ->options([
                    'pending' => 'Ожидает обработки',
                    'processing' => 'В обработке',
                    'shipped' => 'Отправлен',
                    'delivered' => 'Доставлен',
                    'cancelled' => 'Отменен',
                ])
                ->sortable(),
            Number::make('Сумма', 'total_amount')->sortable(),
            Date::make('Дата создания', 'created_at')->sortable(),
        ];
    }

    public function rules(mixed $item): array
    {
        return [
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_country' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string|max:500',
            'shipping_postal_code' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function search(): array
    {
        return ['order_number', 'user.name', 'shipping_name', 'shipping_phone'];
    }

    public function filters(): array
    {
        return [
            Select::make('Статус', 'status')
                ->options([
                    'pending' => 'Ожидает обработки',
                    'processing' => 'В обработке',
                    'shipped' => 'Отправлен',
                    'delivered' => 'Доставлен',
                    'cancelled' => 'Отменен',
                ]),
        ];
    }
} 