<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OnecUser;
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
     * Тело: phone, code и необязательный firebase_token. При успехе номер
     * считается подтверждённым и с ним можно вызывать POST /api/createUser.
     *
     * firebase_token — токен устройства из FCM, нужен для push-уведомлений.
     * Сохраняется только после успешного подтверждения кода и только если
     * клиент уже зарегистрирован; для новой регистрации токен передаётся
     * в POST /api/createUser.
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:32'],
            'code' => ['required', 'string', 'max:10'],
            'firebase_token' => ['nullable', 'string', 'max:512'],
        ]);

        $result = $this->otp->verify($data['phone'], $data['code']);

        if ($result['status'] === 200 && filled($data['firebase_token'] ?? null)) {
            $this->storeFirebaseToken($data['phone'], $data['firebase_token']);
        }

        return response()->json($result['data'], $result['status']);
    }

    /**
     * Привязывает токен устройства к клиенту.
     *
     * Токен снимается с других номеров: одно устройство может отправлять
     * push только текущему вошедшему клиенту, иначе после смены аккаунта
     * уведомления уходили бы прежнему владельцу.
     */
    protected function storeFirebaseToken(string $phone, string $token): void
    {
        $phone = preg_replace('/\D+/', '', $phone) ?? '';

        OnecUser::where('firebase_token', $token)
            ->where('phone', '!=', $phone)
            ->update(['firebase_token' => null]);

        OnecUser::where('phone', $phone)->update(['firebase_token' => $token]);
    }
}
