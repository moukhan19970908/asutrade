<?php

namespace App\Http\Controllers;

use App\Support\Device;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Страница мобильного приложения ASU Auto.
 *
 * Инструкция подбирается по платформе: Android — установка APK,
 * iOS — переход в App Store, компьютер — обе инструкции и QR-код.
 */
class MobileAppController extends Controller
{
    /**
     * GET /prilozhenie
     */
    public function index(Request $request): View|RedirectResponse
    {
        $platform = Device::platform();

        // С iPhone отправляем сразу в App Store: промежуточная страница
        // там не нужна, приложение ставится в один шаг.
        // ?noredirect=1 оставляет страницу — для проверки и для тех,
        // кто пришёл почитать инструкцию с чужого телефона.
        if ($platform === 'ios' && ! $request->boolean('noredirect')) {
            return redirect()->away(config('mobile_app.ios_url'));
        }

        return view('mobile-app.index', [
            'platform' => $platform,
            'apk' => $this->apkInfo(),
        ]);
    }

    /**
     * GET /download/asuauto.apk
     *
     * Отдаём через контроллер, а не прямой ссылкой на файл: не на каждом
     * сервере для .apk настроен правильный Content-Type, а без него
     * Android отказывается ставить скачанное.
     */
    public function download(): BinaryFileResponse
    {
        $path = public_path(config('mobile_app.apk_file'));

        if (! is_file($path)) {
            throw new NotFoundHttpException('Файл приложения не найден');
        }

        return response()->download($path, config('mobile_app.apk_file'), [
            'Content-Type' => 'application/vnd.android.package-archive',
        ]);
    }

    /**
     * Размер, дата и версия APK — читаются с диска, чтобы не расходиться
     * с реально лежащим файлом.
     *
     * @return array{exists: bool, size: ?string, updated: ?string, version: ?string}
     */
    protected function apkInfo(): array
    {
        $path = public_path(config('mobile_app.apk_file'));

        if (! is_file($path)) {
            return ['exists' => false, 'size' => null, 'updated' => null, 'version' => null];
        }

        return [
            'exists' => true,
            'size' => $this->humanSize(filesize($path)),
            'updated' => date('d.m.Y', filemtime($path)),
            'version' => $this->apkVersion($path),
        ];
    }

    protected function humanSize(int $bytes): string
    {
        return $bytes >= 1048576
            ? round($bytes / 1048576).' МБ'
            : round($bytes / 1024).' КБ';
    }

    /**
     * versionName из бинарного AndroidManifest.xml.
     *
     * Полноценный разбор формата здесь избыточен: строки лежат в пуле
     * как UTF-16LE, версии достаточно найти по шаблону. Не нашли —
     * просто не показываем версию.
     */
    protected function apkVersion(string $path): ?string
    {
        try {
            $zip = new \ZipArchive();

            if ($zip->open($path) !== true) {
                return null;
            }

            $manifest = $zip->getFromName('AndroidManifest.xml');
            $zip->close();

            if ($manifest === false) {
                return null;
            }

            $text = @mb_convert_encoding($manifest, 'UTF-8', 'UTF-16LE');

            if (! is_string($text) || ! preg_match('/\b\d+\.\d+\.\d+\b/', $text, $match)) {
                return null;
            }

            return $match[0];
        } catch (\Throwable) {
            return null;
        }
    }
}
