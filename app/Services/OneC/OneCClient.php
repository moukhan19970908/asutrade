<?php

namespace App\Services\OneC;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Клиент HTTP-сервиса 1С AsuAutoV2.
 *
 * Каждый метод возвращает OneCResponse — статус и уже разобранное тело,
 * чтобы контроллер мог отдать наружу тот же код ответа, что вернула 1С.
 */
class OneCClient
{
    public function __construct(
        protected string $baseUrl,
        protected string $login,
        protected string $password,
        protected int $timeout = 20,
        protected bool $verify = true,
    ) {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function checkUser(string $phone): OneCResponse
    {
        return $this->get('/api/checkUser', ['phone' => $this->normalizePhone($phone)]);
    }

    public function createUser(string $name, string $phone): OneCResponse
    {
        return $this->send(fn (PendingRequest $r) => $r->post(
            $this->baseUrl.'/api/createUser',
            ['name' => $name, 'phone' => $this->normalizePhone($phone)],
        ), 'POST /api/createUser');
    }

    public function getLevels(): OneCResponse
    {
        return $this->get('/api/getLevels');
    }

    /**
     * @param  array{clientId?: string|null, phone?: string|null}  $params
     */
    public function getHistory(array $params): OneCResponse
    {
        $query = array_filter([
            'clientId' => $params['clientId'] ?? null,
            'phone' => isset($params['phone']) ? $this->normalizePhone($params['phone']) : null,
        ], fn ($v) => $v !== null && $v !== '');

        return $this->get('/api/getHistory', $query);
    }

    public function findClientByQr(string $code): OneCResponse
    {
        return $this->get('/api/findClientByQr', ['code' => $code]);
    }

    protected function get(string $path, array $query = []): OneCResponse
    {
        return $this->send(
            fn (PendingRequest $r) => $r->get($this->baseUrl.$path, $query),
            'GET '.$path,
        );
    }

    /**
     * @param  callable(PendingRequest): Response  $callback
     */
    protected function send(callable $callback, string $label): OneCResponse
    {
        try {
            $response = $callback($this->request());
        } catch (ConnectionException $e) {
            Log::error('1C: сервис недоступен', ['request' => $label, 'message' => $e->getMessage()]);

            return new OneCResponse(504, [
                'error' => 'Сервис 1С недоступен',
                'detail' => $e->getMessage(),
            ]);
        }

        if ($response->failed()) {
            Log::warning('1C: ошибочный ответ', [
                'request' => $label,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        return new OneCResponse($response->status(), $this->decode($response));
    }

    protected function request(): PendingRequest
    {
        return Http::withBasicAuth($this->login, $this->password)
            ->acceptJson()
            ->withOptions(['verify' => $this->verify])
            ->timeout($this->timeout);
    }

    /**
     * 1С не всегда отдаёт корректный Content-Type, поэтому разбираем тело вручную.
     */
    protected function decode(Response $response): array
    {
        $body = trim($response->body());

        if ($body === '') {
            return $response->successful() ? [] : ['error' => 'Пустой ответ от 1С'];
        }

        $decoded = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['error' => 'Некорректный ответ 1С', 'detail' => $body];
        }

        return is_array($decoded) ? $decoded : ['data' => $decoded];
    }

    /**
     * 1С сама чистит спецсимволы, но отправляем уже нормализованный номер.
     */
    protected function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?? '';
    }
}
