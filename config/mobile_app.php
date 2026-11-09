<?php

/**
 * Мобильное приложение ASU Auto.
 *
 * Версия и размер APK считываются с диска при выводе страницы —
 * после замены файла править конфиг не нужно.
 */
return [

    'name' => 'ASU Auto',
    'package' => 'com.asuauto.app',

    // Страница приложения в App Store (Казахстан).
    'ios_url' => 'https://apps.apple.com/kz/app/asuauto/id6799943262?l=ru',

    // APK лежит в public/. Путь относительно public_path().
    'apk_file' => 'asuauto.apk',

    // Минимальные требования — для текста инструкции.
    'android_min' => '8.0',
    'ios_min' => '13.0',
];
