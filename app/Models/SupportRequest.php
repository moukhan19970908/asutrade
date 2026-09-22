<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Обращение в техподдержку, оставленное через форму на /support.
 */
class SupportRequest extends Model
{
    protected $fillable = [
        'phone',
        'question',
    ];
}
