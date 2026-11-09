<?php

namespace App\Support;

/**
 * Транслитерация кириллицы в слаг для URL.
 *
 * Str::slug() Laravel для русского даёт неудачные формы («Маслянyi»),
 * а казахские буквы не обрабатывает вовсе, поэтому таблица своя.
 */
class Slugger
{
    /** Длина слага без учёта суффикса уникальности. */
    public const MAX_LENGTH = 90;

    /**
     * Русские и казахские буквы. Порядок важен: многобуквенные
     * замены должны идти до однобуквенных (щ до ш и т.д.).
     */
    protected const MAP = [
        // Казахские — до русских, иначе «і» уйдёт в общую ветку
        'ә' => 'a', 'ғ' => 'g', 'қ' => 'k', 'ң' => 'ng', 'ө' => 'o',
        'ұ' => 'u', 'ү' => 'u', 'һ' => 'h', 'і' => 'i',

        'щ' => 'sch', 'ш' => 'sh', 'ч' => 'ch', 'ж' => 'zh',
        'ю' => 'yu', 'я' => 'ya', 'ё' => 'e',

        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
        'е' => 'e', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
        'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p',
        'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f',
        'х' => 'h', 'ц' => 'c', 'ы' => 'y', 'э' => 'e',
        'ъ' => '', 'ь' => '',

        // Часто встречается в названиях из 1С
        '№' => 'n', '&' => '-and-', '+' => '-plus-', '/' => '-', '\\' => '-',
    ];

    /**
     * Базовый слаг из произвольной строки.
     * Возвращает '' только если в строке не было ни одного пригодного символа.
     */
    public static function make(string $value): string
    {
        $value = mb_strtolower(trim($value), 'UTF-8');
        $value = strtr($value, self::MAP);

        // Всё, что осталось за пределами латиницы и цифр, становится дефисом.
        $value = preg_replace('/[^a-z0-9]+/u', '-', $value) ?? '';
        $value = trim($value, '-');

        if (mb_strlen($value) > self::MAX_LENGTH) {
            $value = mb_substr($value, 0, self::MAX_LENGTH);
            $value = preg_replace('/-[^-]*$/', '', $value) ?: $value;
        }

        return trim($value, '-');
    }

    /**
     * Слаг, пригодный для URL: не пустой и не состоящий из одних цифр.
     *
     * Чисто цифровой слаг ломает маршрутизацию — /catalog/123 зарезервирован
     * под 301 со старых адресов товаров, где 123 это id.
     *
     * @param  string  $fallbackPrefix  префикс для вырожденных случаев (c, p, b)
     */
    public static function safe(string $value, string $fallbackPrefix, int|string $id): string
    {
        $slug = self::make($value);

        if ($slug === '' || preg_match('/^\d+$/', $slug)) {
            $slug = trim($fallbackPrefix.'-'.($slug !== '' ? $slug : $id), '-');
        }

        return $slug;
    }
}
