<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OneC\OneCClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Прокси к HTTP-сервису 1С AsuAutoV2 для мобильного приложения ASU Auto.
 *
 * Контракт запросов и ответов совпадает с документацией 1С:
 * коды состояния и тело ответа пробрасываются как есть.
 */
class OneCController extends Controller
{
    public function __construct(protected OneCClient $onec) {}

    /**
     * GET /api/checkUser?phone=77771112233
     */
    public function checkUser(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ]);

        return $this->onec->checkUser($data['phone'])->toJsonResponse();
    }

    /**
     * POST /api/createUser
     */
    public function createUser(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        return $this->onec->createUser($data['name'], $data['phone'])->toJsonResponse();
    }

    /**
     * GET /api/getLevels
     */
    public function getLevels(): JsonResponse
    {
        return $this->onec->getLevels()->toJsonResponse();
    }

    /**
     * GET /api/getHistory?phone=... | ?clientId=...
     */
    public function getHistory(Request $request): JsonResponse
    {
        $data = $request->validate([
            'clientId' => ['required_without:phone', 'nullable', 'string', 'max:64'],
            'phone' => ['required_without:clientId', 'nullable', 'string', 'max:20'],
        ]);

        return $this->onec->getHistory($data)->toJsonResponse();
    }

    /**
     * GET /api/findClientByQr?code=...
     */
    public function findClientByQr(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:255'],
        ]);

        return $this->onec->findClientByQr($data['code'])->toJsonResponse();
    }
}
