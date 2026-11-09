<?php

/**
 * Магазины сети.
 *
 * Единственный источник правды: отсюда их берут футер, страницы городов
 * и разметка schema.org. Раньше адреса жили только в вёрстке футера
 * и дублировались в разметке — любое расхождение было вопросом времени.
 */
return [

    'phone' => '+77788084030',
    'phone_display' => '+7 (778) 808 40 30',
    'whatsapp' => 'https://wa.me/77788084030',

    'cities' => [
        'aktau' => [
            'name' => 'Актау',
            'name_prepositional' => 'Актау',

            // Склады 1С, относящиеся к городу. Сверяется с warehouses.name
            // без учёта регистра. Посмотреть реальные имена:
            // php artisan stores:warehouses
            'warehouses' => ['Актау'],

            'points' => [
                [
                    'slug' => 'kulager',
                    'street' => '25-й микрорайон, БЦ Кулагер',
                    'map' => 'https://go.2gis.com/8ETIC',
                ],
                [
                    'slug' => '6a',
                    'street' => 'Микрорайон 6а, 20/1',
                    'map' => 'https://go.2gis.com/bxc7n',
                ],
                [
                    'slug' => '28a',
                    'street' => 'Микрорайон 28а, 30/22',
                    'map' => 'https://go.2gis.com/740Gn',
                ],
            ],
        ],

        'zhanaozen' => [
            'name' => 'Жанаозен',
            'name_prepositional' => 'Жанаозене',
            'warehouses' => ['Жанаозен'],
            'points' => [
                [
                    'slug' => 'centr',
                    'street' => 'Улица Кайсагалиев Гарипулла, 8/1',
                    'map' => 'https://go.2gis.com/790d9',
                ],
            ],
        ],

        'aktobe' => [
            'name' => 'Актобе',
            'name_prepositional' => 'Актобе',
            'warehouses' => ['Актобе'],
            'points' => [
                [
                    'slug' => 'nokina',
                    'street' => 'Проспект Кенес Нокина, 3д',
                    'map' => 'https://go.2gis.com/npUsZ',
                ],
            ],
        ],
    ],
];
