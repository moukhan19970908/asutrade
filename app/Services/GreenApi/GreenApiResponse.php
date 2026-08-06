<?php

namespace App\Services\GreenApi;

use Illuminate\Http\JsonResponse;

/**
 * Ответ Green API: код состояния и разобранное тело.
 */
class GreenApiResponse
{
    public function __construct(
        public readonly int $status,
        public readonly array $data,
    ) {}

    public function successful(): bool
    {
        return $this->status >= 200 && $this->status < 300;
    }

    /**
     * Идентификатор отправленного сообщения (idMessage), если отправка удалась.
     */
    public function messageId(): ?string
    {
        $id = $this->data['idMessage'] ?? null;

        return is_string($id) && $id !== '' ? $id : null;
    }

    public function toJsonResponse(): JsonResponse
    {
        return response()->json($this->data, $this->status);
    }
}
