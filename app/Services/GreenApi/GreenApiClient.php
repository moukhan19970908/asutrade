<?php

namespace App\Services\GreenApi;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Клиент Green API (WhatsApp).
 *
 * Все методы инстанса вызываются по схеме
 * {apiUrl}/waInstance{idInstance}/{method}/{apiTokenInstance}
 * и возвращают GreenApiResponse — статус и разобранное тело ответа.
 *
 * @see https://green-api.com/en/docs/api/sending/SendMessage/
 */
class GreenApiClient
{
    /** Максимальная длина текста сообщения по документации Green API. */
    public const MAX_MESSAGE_LENGTH = 20000;

    public function __construct(
        protected string $apiUrl,
        protected string $idInstance,
        protected string $apiToken,
        protected int $timeout = 20,
        protected bool $verify = true,
    ) {
        $this->apiUrl = rtrim($apiUrl, '/');
    }

    /**
     * Инстанс настроен: без idInstance/apiToken отправлять некуда.
     */
    public function configured(): bool
    {
        return $this->idInstance !== '' && $this->apiToken !== '';
    }

    /**
     * Отправка текстового сообщения на номер телефона.
     *
     * POST /waInstance{id}/sendMessage/{token}
     * Успешный ответ: {"idMessage": "3EB0C767D097B7C7C030"}
     */
    public function sendMessage(string $phone, string $message, ?string $quotedMessageId = null): GreenApiResponse
    {
        $chatId = $this->chatId($phone);

        if ($chatId === null) {
            return new GreenApiResponse(422, [
                'error' => 'Некорректный номер телефона',
                'detail' => 'Ожидается номер из 10–16 цифр с кодом страны',
            ]);
        }

        if (! $this->configured()) {
            Log::warning('GreenAPI: инстанс не настроен, сообщение не отправлено', ['chatId' => $chatId]);

            return new GreenApiResponse(503, [
                'error' => 'Интеграция WhatsApp не настроена',
            ]);
        }

        $payload = array_filter([
            'chatId' => $chatId,
            'message' => mb_substr($message, 0, self::MAX_MESSAGE_LENGTH),
            'quotedMessageId' => $quotedMessageId,
        ], fn ($v) => $v !== null);

        return $this->post('sendMessage', $payload, 'sendMessage '.$chatId);
    }

    /**
     * Проверка, зарегистрирован ли номер в WhatsApp.
     *
     * POST /waInstance{id}/checkWhatsapp/{token}
     * Ответ: {"existsWhatsapp": true, ...}
     */
    public function checkWhatsapp(string $phone): GreenApiResponse
    {
        $digits = $this->normalizePhone($phone);

        if ($digits === '') {
            return new GreenApiResponse(422, ['error' => 'Некорректный номер телефона']);
        }

        if (! $this->configured()) {
            return new GreenApiResponse(503, ['error' => 'Интеграция WhatsApp не настроена']);
        }

        return $this->post('checkWhatsapp', ['chatId' => $digits], 'checkWhatsapp '.$digits);
    }

    /**
     * chatId личного чата: только цифры номера + суффикс @c.us.
     * Групповые чаты (…@g.us) передаются как есть.
     */
    public function chatId(string $phone): ?string
    {
        if (str_ends_with($phone, '@c.us') || str_ends_with($phone, '@g.us')) {
            return $phone;
        }

        $digits = $this->normalizePhone($phone);

        if (strlen($digits) < 10 || strlen($digits) > 16) {
            return null;
        }

        return $digits.'@c.us';
    }

    protected function post(string $method, array $payload, string $label): GreenApiResponse
    {
        $url = sprintf('%s/waInstance%s/%s/%s', $this->apiUrl, $this->idInstance, $method, $this->apiToken);

        try {
            $response = $this->request()->post($url, $payload);
        } catch (ConnectionException $e) {
            Log::error('GreenAPI: сервис недоступен', ['request' => $label, 'message' => $e->getMessage()]);

            return new GreenApiResponse(504, [
                'error' => 'Сервис WhatsApp недоступен',
                'detail' => $e->getMessage(),
            ]);
        }

        if ($response->failed()) {
            // 400 — инстанс не авторизован/не запущен, 401/403 — неверные реквизиты,
            // 429 — превышена частота запросов, 466 — исчерпан лимит тарифа.
            Log::warning('GreenAPI: ошибочный ответ', [
                'request' => $label,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        return new GreenApiResponse($response->status(), $this->decode($response));
    }

    protected function request(): PendingRequest
    {
        return Http::acceptJson()
            ->withOptions(['verify' => $this->verify])
            ->timeout($this->timeout);
    }

    /**
     * При ошибках Green API нередко отдаёт текст, а не JSON.
     */
    protected function decode(Response $response): array
    {
        $body = trim($response->body());

        if ($body === '') {
            return $response->successful() ? [] : ['error' => 'Пустой ответ Green API'];
        }

        $decoded = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['error' => 'Некорректный ответ Green API', 'detail' => $body];
        }

        return is_array($decoded) ? $decoded : ['data' => $decoded];
    }

    protected function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?? '';
    }
}
