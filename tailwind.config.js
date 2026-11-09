/** @type {import('tailwindcss').Config} */
export default {
    // Сканируем всё, где могут встретиться классы. Компилированные вьюхи
    // из storage сюда не включаем: там лежит кеш, который устаревает,
    // а исходные blade-файлы покрывают тот же набор классов.
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: [
                    'Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif',
                    'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji',
                ],
            },
        },
    },
    plugins: [],
};
