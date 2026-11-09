@extends('layouts.app')

@php($trail = [['name' => 'Приложение']])

@section('title', 'Приложение ASU Auto — скачать для Android и iPhone')
@section('description', 'Мобильное приложение ASU Auto: каталог автозапчастей, история замен масла и программа лояльности. Установка на Android и iPhone.')

@push('schema')
    @include('partials.schema-breadcrumbs', ['trail' => $trail])
    @include('partials.schema-app')
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    @include('partials.breadcrumbs', ['trail' => $trail])

    <div class="max-w-4xl">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Приложение ASU Auto</h1>
        <p class="text-gray-600 mb-8">
            Каталог запчастей, история замен масла и программа лояльности в телефоне.
            @if($apk['version'])
                Текущая версия для Android — {{ $apk['version'] }}.
            @endif
        </p>

        {{-- ================= ANDROID ================= --}}
        @if($platform === 'android')
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <i class="fab fa-android text-3xl text-green-600"></i>
                    <h2 class="text-xl font-semibold text-gray-900">Установка на Android</h2>
                </div>

                @include('mobile-app.partials.android-steps', ['apk' => $apk])
            </div>

            <p class="text-sm text-gray-500">
                У вас iPhone? <a href="{{ config('mobile_app.ios_url') }}" target="_blank" rel="noopener noreferrer"
                   class="text-blue-600 hover:underline">Приложение в App Store</a>.
            </p>

        {{-- ================= IOS (только при ?noredirect=1) ================= --}}
        @elseif($platform === 'ios')
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <i class="fab fa-apple text-3xl text-gray-800"></i>
                    <h2 class="text-xl font-semibold text-gray-900">Установка на iPhone</h2>
                </div>

                @include('mobile-app.partials.ios-steps')
            </div>

        {{-- ================= КОМПЬЮТЕР ================= --}}
        @else
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-5 mb-8">
                <div class="md:flex md:items-center md:gap-6">
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 mb-1">Вы открыли сайт с компьютера</div>
                        <p class="text-gray-600 text-sm">
                            Приложение устанавливается на телефон. Отсканируйте код камерой телефона,
                            чтобы открыть эту страницу на нём, — или воспользуйтесь инструкцией ниже.
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 text-center">
                        <div id="app-qr" class="inline-block bg-white p-2 rounded"></div>
                        <div id="app-qr-fallback" class="text-xs text-gray-500 mt-2 break-all max-w-[180px]">
                            {{ route('mobile-app.index') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <i class="fab fa-android text-3xl text-green-600"></i>
                        <h2 class="text-xl font-semibold text-gray-900">Android</h2>
                    </div>
                    @include('mobile-app.partials.android-steps', ['apk' => $apk])
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <i class="fab fa-apple text-3xl text-gray-800"></i>
                        <h2 class="text-xl font-semibold text-gray-900">iPhone и iPad</h2>
                    </div>
                    @include('mobile-app.partials.ios-steps')
                </div>
            </div>
        @endif

        <div class="mt-10 text-sm text-gray-500">
            Не получается установить? Напишите нам в
            <a href="{{ config('stores.whatsapp') }}" target="_blank" rel="noopener noreferrer"
               class="text-blue-600 hover:underline">WhatsApp</a>
            или на <a href="{{ route('support') }}" class="text-blue-600 hover:underline">страницу техподдержки</a>.
        </div>
    </div>
</div>
@endsection

@if($platform === 'desktop')
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <script>
            // Если библиотека не загрузилась, под кодом остаётся обычная ссылка.
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof QRCode === 'undefined') return;

                new QRCode(document.getElementById('app-qr'), {
                    text: @json(route('mobile-app.index')),
                    width: 140,
                    height: 140,
                    correctLevel: QRCode.CorrectLevel.M
                });

                document.getElementById('app-qr-fallback').classList.add('hidden');
            });
        </script>
    @endpush
@endif
