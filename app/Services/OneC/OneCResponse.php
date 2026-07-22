<?php

namespace App\Services\OneC;

use Illuminate\Http\JsonResponse;

/**
 * Ответ HTTP-сервиса 1С: код состояния и разобранное тело.
 */
class OneCResponse
{
    public function __construct(
        public readonly int $status,
        public readonly array $data,
    ) {}

    public function successful(): bool
    {
        return $this->status >= 200 && $this->status < 300;
    }

    public function toJsonResponse(): JsonResponse
    {
        return response()->json($this->data, $this->status);
    }
}
