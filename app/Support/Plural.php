<?php

namespace App\Support;

/**
 * Склонение существительных при числительных.
 *
 * Нужно там, где число приходит из данных: «1 позиция», «2 позиции»,
 * «5 позиций». Без этого городские страницы и ответы в FAQ читались бы
 * как машинный перевод — а их задача выглядеть написанными человеком.
 */
class Plural
{
    public static function ru(int $count, string $one, string $few, string $many): string
    {
        $mod100 = $count % 100;
        $mod10 = $count % 10;

        return match (true) {
            $mod100 >= 11 && $mod100 <= 14 => $many,
            $mod10 === 1 => $one,
            $mod10 >= 2 && $mod10 <= 4 => $few,
            default => $many,
        };
    }

    /** Число вместе со словом: «5 позиций». */
    public static function with(int $count, string $one, string $few, string $many): string
    {
        return $count.' '.self::ru($count, $one, $few, $many);
    }
}
