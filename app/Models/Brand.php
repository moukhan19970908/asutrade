<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'is_popular',
    ];

    protected function slugFallbackPrefix(): string
    {
        return 'brand';
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
