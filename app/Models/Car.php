<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'name',
        'model_id',
        'vin_code',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(OnecUser::class, 'user_id');
    }

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'model_id');
    }

    public function oilChanges(): HasMany
    {
        return $this->hasMany(OilChange::class);
    }
}
