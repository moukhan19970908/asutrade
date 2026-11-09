<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Один сайт — один хост.
 *
 * До этого https://asu-trade.com и https://www.asu-trade.com отдавали
 * одинаковое содержимое с кодом 200, и каждый ставил canonical сам на себя.
 * Для поиска это два разных сайта, делящих между собой ссылочный вес.
 *
 * Переносим на хост из APP_URL постоянным редиректом. Работает только
 * для безопасных запросов: 301 на POST потерял бы тело формы.
 */
class ForceCanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        $canonical = parse_url((string) config('app.url'), PHP_URL_HOST);

        if (
            $canonical
            && $request->isMethodSafe()
            && $request->getHost() !== $canonical
            && ! app()->environment('local', 'testing')
        ) {
            $target = rtrim((string) config('app.url'), '/').'/'.ltrim($request->getRequestUri(), '/');

            return redirect()->away($target, 301);
        }

        return $next($request);
    }
}
