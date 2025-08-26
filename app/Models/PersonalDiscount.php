<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'discount_percent',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_percent' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
} 