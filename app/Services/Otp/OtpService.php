<?php

namespace App\Services\Otp;

use App\Models\OtpCode;
use App\Services\GreenApi\GreenApiClient;
use Illuminate\Support\Facades\Log;

/**
 * Одноразовые коды подтверждения номера, доставляемые в WhatsApp.
 *
 * Каждый метод возвращает ['status' => int, 'data' => array] — готовый
 * код состояния и тело ответа в том же формате, что и остальные методы API.
 */
class OtpService
{
    public function __construct(protected GreenApiClient $greenApi) {}

    /**
     * Выдаёт новый код и отправляет его в WhatsApp.
     *
     * @return array{status: int, data: array}
     */
    public function send(string $phone): array
    {
        $phone = $this->normalizePhone($phone);

        if ($phone === '') {
            return ['status' => 400, 'data' => ['error' => 'Некорректный номер телефона']];
        }

        $last = OtpCode::latestForPhone($phone)->first();
        $interval = (int) config('services.otp.resend_interval');

        // Защита от спама: повторная отправка не чаще, чем раз в resend_interval секунд.
        if ($last && $last->created_at->addSeconds($interval)->isFuture()) {
            $retryAfter = (int) ceil(now()->diffInSeconds($last->created_at->addSeconds($interval), absolute: true));

            return ['status' => 429, 'data' => [
                'error' => 'Код уже отправлен, попробуйте позже',
                'retryAfter' => $retryAfter,
            ]];
        }

        $ttl = (int) config('services.otp.ttl');
        $code = $this->generateCode();

        $message = strtr((string) config('services.otp.message'), [
            ':code' => $code,
            ':minutes' => (string) max(1, (int) round($ttl / 60)),
        ]);

        $response = $this->greenApi->sendMessage($phone, $message);

        if (! $response->successful()) {
            Log::warning('OTP: код не отправлен', [
                'phone' => $phone,
                'status' => $response->status,
                'body' => $response->data,
            ]);

            return ['status' => $response->status, 'data' => [
                'error' => 'Не удалось отправить код в WhatsApp',
                'detail' => $response->data['message'] ?? $response->data['error'] ?? null,
            ]];
        }

        OtpCode::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => now()->addSeconds($ttl),
        ]);

        return ['status' => 200, 'data' => [
            'status' => 'sent',
            'phone' => $phone,
            'expiresIn' => $ttl,
            'idMessage' => $response->messageId(),
        ]];
    }

    /**
     * Проверяет код. При успехе номер считается подтверждённым
     * в течение services.otp.verification_ttl секунд.
     *
     * @return array{status: int, data: array}
     */
    public function verify(string $phone, string $code): array
    {
        $phone = $this->normalizePhone($phone);
        $otp = OtpCode::latestForPhone($phone)->first();

        if (! $otp || $otp->expired()) {
            return ['status' => 400, 'data' => ['error' => 'Код не найден или истёк, запросите новый']];
        }

        if ($otp->consumed_at) {
            return ['status' => 400, 'data' => ['error' => 'Код уже использован, запросите новый']];
        }

        if ($otp->verified_at) {
            return ['status' => 200, 'data' => ['status' => 'verified', 'phone' => $phone]];
        }

        $maxAttempts = (int) config('services.otp.max_attempts');

        if ($otp->attempts >= $maxAttempts) {
            return ['status' => 429, 'data' => ['error' => 'Превышено число попыток, запросите новый код']];
        }

        $otp->increment('attempts');

        if (! hash_equals($otp->code, trim($code))) {
            return ['status' => 400, 'data' => [
                'error' => 'Неверный код',
                'attemptsLeft' => max(0, $maxAttempts - $otp->attempts),
            ]];
        }

        $otp->forceFill(['verified_at' => now(), 'consumed_at' => null])->save();

        return ['status' => 200, 'data' => ['status' => 'verified', 'phone' => $phone]];
    }

    /**
     * Номер подтверждён кодом и подтверждение ещё не использовано.
     */
    public function verified(string $phone): bool
    {
        return $this->activeVerification($this->normalizePhone($phone)) !== null;
    }

    /**
     * Гасит подтверждение после успешной регистрации, чтобы одним кодом
     * нельзя было создать несколько клиентов.
     */
    public function consume(string $phone): bool
    {
        $otp = $this->activeVerification($this->normalizePhone($phone));

        if (! $otp) {
            return false;
        }

        $otp->forceFill(['consumed_at' => now()])->save();

        return true;
    }

    protected function activeVerification(string $phone): ?OtpCode
    {
        $otp = OtpCode::latestForPhone($phone)->whereNotNull('verified_at')->whereNull('consumed_at')->first();

        if (! $otp) {
            return null;
        }

        $window = (int) config('services.otp.verification_ttl');

        return $otp->verified_at->addSeconds($window)->isFuture() ? $otp : null;
    }

    protected function generateCode(): string
    {
        $length = max(4, min(8, (int) config('services.otp.length')));

        return str_pad((string) random_int(0, (10 ** $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    protected function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?? '';
    }
}
