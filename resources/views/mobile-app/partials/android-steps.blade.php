{{-- Шаги установки APK. Ожидает $apk из MobileAppController::apkInfo(). --}}
@if($apk['exists'])
    <a href="{{ route('mobile-app.download') }}"
       class="inline-flex items-center gap-2 py-3 px-6 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 transition-colors duration-200 mb-2">
        <i class="fas fa-download"></i>
        Скачать APK
    </a>
    <div class="text-xs text-gray-500 mb-5">
        {{ $apk['size'] }}@if($apk['version']) · версия {{ $apk['version'] }}@endif · обновлён {{ $apk['updated'] }}
    </div>
@else
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded p-3 mb-5 text-sm">
        Файл приложения временно недоступен. Напишите нам в WhatsApp — пришлём ссылку.
    </div>
@endif

<ol class="space-y-3 text-gray-700 text-sm list-decimal list-inside">
    <li>Нажмите «Скачать APK». Браузер предупредит, что такие файлы могут навредить устройству, — это стандартное предупреждение для любого файла вне Google Play. Подтвердите загрузку.</li>
    <li>Откройте скачанный файл: в шторке уведомлений или через «Файлы» → «Загрузки».</li>
    <li>Android попросит разрешить установку из этого источника. Нажмите «Настройки» и включите «Разрешить установку приложений».</li>
    <li>Вернитесь назад и нажмите «Установить».</li>
    <li>Откройте ASU Auto и войдите по номеру телефона — код придёт в WhatsApp.</li>
</ol>

<p class="text-xs text-gray-500 mt-4">
    Требуется Android {{ config('mobile_app.android_min') }} или новее. Разрешение на установку
    можно отключить обратно сразу после установки.
</p>
