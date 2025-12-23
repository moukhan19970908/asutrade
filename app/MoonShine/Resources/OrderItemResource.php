<?php

namespace App\MoonShine\Resources;

use App\Models\OrderItem;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Number;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;



class OrderItemResource extends ModelResource
{
    protected string $model = OrderItem::class;

    protected string $title = 'Элементы заказа';

    public function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Заказ', 'order', 'order_number')->sortable(),
            BelongsTo::make('Товар', 'product', 'name')->sortable(),
            Text::make('Название товара', 'product_name')->sortable(),
            Number::make('Цена', 'price')->sortable(),
            Number::make('Количество', 'quantity')->sortable(),
            Number::make('Сумма', 'subtotal')->sortable(),
        ];
    }

    public function detailFields(): iterable
    {
        return [
            ID::make(),

            BelongsTo::make('Заказ', 'order', 'order_number'),
            BelongsTo::make('Товар', 'product', 'name'),

            Text::make('Название товара', 'product_name'),
            Number::make('Цена', 'price'),
            Number::make('Количество', 'quantity'),
            Number::make('Сумма', 'subtotal'),
        ];
    }

    public function formFields(): iterable
    {
        return [
            BelongsTo::make('Заказ', 'order', 'order_number')->readonly(),
            BelongsTo::make('Товар', 'product', 'name')->readonly(),
            Text::make('Название товара', 'product_name')->readonly(),
            Number::make('Цена', 'price')->readonly(),
            Number::make('Количество', 'quantity')->readonly(),
            Number::make('Сумма', 'subtotal')->readonly(),
        ];
    }

    public function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Заказ', 'order', 'order_number')->sortable(),
            BelongsTo::make('Товар', 'product', 'name')->sortable(),
            Text::make('Название товара', 'product_name')->sortable(),
            Number::make('Цена', 'price')->sortable(),
            Number::make('Количество', 'quantity')->sortable(),
            Number::make('Сумма', 'subtotal')->sortable(),
        ];
    }

    public function search(): array
    {
        return ['product_name', 'order.order_number'];
    }
}
