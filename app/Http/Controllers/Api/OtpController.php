<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Otp\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Подтверждение номера телефона одноразовым кодом из WhatsApp.
 */
class OtpController extends Controller
{
    public function __construct(protected OtpService $otp) {}

    /**
     * POST /api/sendOtp
     *
     * Тело: phone. Отправляет код в WhatsApp.
     * Ответ: {"status":"sent","phone":"...","expiresIn":300,"idMessage":"..."}
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:32'],
        ]);

        $result = $this->otp->send($data['phone']);

        return response()->json($result['data'], $result['status']);
    }

    /**
     * POST /api/verifyOtp
     *
     * Тело: phone, code. При успехе номер считается подтверждённым
     * и с ним можно вызывать POST /api/createUser.
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:32'],
            'code' => ['required', 'string', 'max:10'],
        ]);

        $result = $this->otp->verify($data['phone'], $data['code']);

        return response()->json($result['data'], $result['status']);
    }
}
