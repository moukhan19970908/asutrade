<?php

namespace App\Support;

/**
 * Канонический адрес и правила индексации страницы.
 */
class Seo
{
    /** Префиксы локали: /ru/catalog и /catalog отдают один и тот же контент. */
    protected const LOCALES = ['ru', 'kz'];

    /** Разделы, которым в индексе делать нечего. */
    protected const PRIVATE_SEGMENTS = ['cart', 'checkout', 'orders', 'profile', 'login', 'admin'];

    /**
     * Канонический адрес: без префикса локали и без лишних параметров.
     *
     * Номер страницы сохраняется — иначе вторая и последующие страницы
     * каталога указывали бы на первую, и их товары выпали бы из индекса.
     */
    public static function canonical(): string
    {
        $segments = request()->segments();

        if (in_array($segments[0] ?? '', self::LOCALES, true)) {
            array_shift($segments);
        }

        $url = rtrim(url('/'), '/');

        if ($segments !== []) {
            $url .= '/'.implode('/', $segments);
        }

        $page = (int) request()->query('page', 1);

        return $page > 1 ? $url.'?page='.$page : $url;
    }

    /**
     * Значение meta robots или null, если страница индексируется обычным образом.
     */
    public static function robots(): ?string
    {
        $first = request()->segments()[0] ?? '';

        if (in_array($first, self::LOCALES, true)) {
            $first = request()->segments()[1] ?? '';
        }

        if (in_array($first, self::PRIVATE_SEGMENTS, true)) {
            return 'noindex, follow';
        }

        // Страницы результатов поиска бесконечны и не несут самостоятельной ценности.
        if (filled(request()->query('search'))) {
            return 'noindex, follow';
        }

        return null;
    }
}
