<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Одноразовый код подтверждения, отправленный клиенту в WhatsApp.
 */
class OtpCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'code',
        'attempts',
        'expires_at',
        'verified_at',
        'consumed_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
            'consumed_at' => 'datetime',
        ];
    }

    public function expired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Последний выданный код для номера.
     */
    public function scopeLatestForPhone(Builder $query, string $phone): Builder
    {
        return $query->where('phone', $phone)->orderByDesc('id');
    }
}
