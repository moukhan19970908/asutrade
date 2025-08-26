<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'description',
        'price',
        'image',
        'in_stock',
        'code',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'in_stock' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_product')
            ->withPivot('stock')
            ->withTimestamps();
    }

    public function personalDiscounts(): HasMany
    {
        return $this->hasMany(PersonalDiscount::class);
    }

    /**
     * Получить персональную скидку для пользователя
     */
    public function getPersonalDiscountForUser($userId)
    {
        return $this->personalDiscounts()
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Получить цену со скидкой для пользователя
     */
    public function getDiscountedPrice($userId)
    {
        $discount = $this->getPersonalDiscountForUser($userId);
        if ($discount) {
            return $this->price * (1 - $discount->discount_percent / 100);
        }
        return $this->price;
    }

    /**
     * Проверить, есть ли у пользователя персональная скидка
     */
    public function hasPersonalDiscount($userId)
    {
        return $this->personalDiscounts()
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->exists();
    }
}
