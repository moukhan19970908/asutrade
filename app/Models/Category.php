<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'image',
        'parent_id'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class , 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class , 'parent_id');
    }

    public function getTotalProductsCountAttribute()
    {
        $count = $this->products()->count();

        foreach ($this->children as $child) {
            $count += $child->total_products_count;
        }

        return $count;
    }

    public function isAncestorOf($category)
    {
        if (!$category) {
            return false;
        }
        if ($this->id === $category->parent_id) {
            return true;
        }
        return $this->isAncestorOf($category->parent);
    }
}
