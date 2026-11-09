<?php

namespace App\Support;

/**
 * Определение платформы посетителя по User-Agent.
 *
 * Нужно только для того, чтобы показать нужную инструкцию по установке
 * приложения. Доступ к контенту от этого не зависит — на любой платформе
 * страница открывается целиком.
 */
class Device
{
    public static function isIos(?string $userAgent = null): bool
    {
        $agent = $userAgent ?? request()->userAgent() ?? '';

        // iPadOS 13+ в Safari по умолчанию представляется как Macintosh —
        // отличить его от обычного Mac на сервере нельзя, такой планшет
        // попадёт в ветку «компьютер» и увидит обе инструкции.
        return (bool) preg_match('/\b(iPhone|iPad|iPod)\b/i', $agent);
    }

    public static function isAndroid(?string $userAgent = null): bool
    {
        $agent = $userAgent ?? request()->userAgent() ?? '';

        return stripos($agent, 'android') !== false;
    }

    /**
     * Платформа одним словом: ios, android или desktop.
     */
    public static function platform(?string $userAgent = null): string
    {
        return match (true) {
            self::isIos($userAgent) => 'ios',
            self::isAndroid($userAgent) => 'android',
            default => 'desktop',
        };
    }
}
