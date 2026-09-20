<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarModel extends Model
{
    protected $fillable = [
        'name',
        'mark_id',
    ];

    public function mark(): BelongsTo
    {
        return $this->belongsTo(CarMark::class, 'mark_id');
    }
}
