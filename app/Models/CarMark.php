<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarMark extends Model
{
    protected $fillable = [
        'name',
    ];

    public function carModels(): HasMany
    {
        return $this->hasMany(CarModel::class, 'mark_id');
    }
}
