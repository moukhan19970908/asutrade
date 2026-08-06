<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GreenApi\GreenApiClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Отправка сообщений в WhatsApp через Green API.
 */
class WhatsAppController extends Controller
{
    public function __construct(protected GreenApiClient $greenApi) {}

    /**
     * POST /api/sendWhatsapp
     *
     * Тело: phone (номер с кодом страны), message (до 20000 символов).
     * Успешный ответ Green API: {"idMessage": "..."} — пробрасывается как есть.
     */
    public function sendWhatsapp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:32'],
            'message' => ['required', 'string', 'max:'.GreenApiClient::MAX_MESSAGE_LENGTH],
            'quotedMessageId' => ['nullable', 'string', 'max:255'],
        ]);

        return $this->greenApi
            ->sendMessage($data['phone'], $data['message'], $data['quotedMessageId'] ?? null)
            ->toJsonResponse();
    }

    /**
     * GET /api/checkWhatsapp?phone=77771112233
     *
     * Проверяет, зарегистрирован ли номер в WhatsApp.
     */
    public function checkWhatsapp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:32'],
        ]);

        return $this->greenApi->checkWhatsapp($data['phone'])->toJsonResponse();
    }
}
