{{-- Шаги установки на iOS: всё через App Store. --}}
<a href="{{ config('mobile_app.ios_url') }}" target="_blank" rel="noopener noreferrer"
   class="inline-flex items-center gap-2 py-3 px-6 bg-gray-900 text-white font-semibold rounded-md hover:bg-gray-800 transition-colors duration-200 mb-5">
    <i class="fab fa-apple"></i>
    Открыть в App Store
</a>

<ol class="space-y-3 text-gray-700 text-sm list-decimal list-inside">
    <li>Нажмите «Открыть в App Store» — откроется страница ASU Auto.</li>
    <li>Нажмите «Загрузить» и подтвердите Face ID, Touch ID или паролем Apple ID.</li>
    <li>Дождитесь установки и откройте приложение.</li>
    <li>Войдите по номеру телефона — код придёт в WhatsApp.</li>
</ol>

<p class="text-xs text-gray-500 mt-4">
    Требуется iOS {{ config('mobile_app.ios_min') }} или новее. Ничего скачивать вручную не нужно —
    установка идёт обычным путём через App Store.
</p>
