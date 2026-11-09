<?php

namespace App\Models\Concerns;

use App\Support\Slugger;
use Illuminate\Database\Eloquent\Model;

/**
 * Слаг в URL вместо id.
 *
 * Модель сама проставляет слаг при создании и при смене имени,
 * а в маршрутах разрешается и по слагу, и по id — чтобы старые
 * ссылки и записи без слага не отдавали 404.
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function (Model $model) {
            if (blank($model->slug) || $model->isDirty('name')) {
                $model->slug = $model->makeUniqueSlug((string) $model->name);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Пока слаг не проставлен, ссылка должна вести хотя бы на id.
     */
    public function getRouteKey(): mixed
    {
        return $this->slug ?: $this->getKey();
    }

    /**
     * Сначала слаг, потом id: так продолжают работать ссылки,
     * сохранённые до перехода на слаги.
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        if ($field !== null) {
            return $this->where($field, $value)->first();
        }

        $model = $this->where('slug', $value)->first();

        if ($model === null && ctype_digit((string) $value)) {
            $model = $this->whereKey($value)->first();
        }

        return $model;
    }

    /**
     * Уникальный слаг: при совпадении дописывается артикул,
     * а если и он занят — числовой суффикс.
     */
    public function makeUniqueSlug(string $source): string
    {
        $base = Slugger::safe($source, $this->slugFallbackPrefix(), $this->getKey() ?? 'x');
        $slug = $base;
        $suffixes = array_filter([$this->slugDisambiguator()]);
        $attempt = 1;

        while ($this->slugTaken($slug)) {
            $slug = $suffixes !== []
                ? $base.'-'.Slugger::make((string) array_shift($suffixes))
                : $base.'-'.(++$attempt);

            // Артикул мог оказаться пустым после транслитерации.
            $slug = trim($slug, '-') ?: $base.'-'.(++$attempt);
        }

        return $slug;
    }

    protected function slugTaken(string $slug): bool
    {
        // Без глобальных скоупов: скрытая от текущего пользователя запись
        // всё равно занимает слаг.
        return static::query()
            ->withoutGlobalScopes()
            ->where('slug', $slug)
            ->when($this->exists, fn ($query) => $query->whereKeyNot($this->getKey()))
            ->exists();
    }

    /**
     * Чем разрешать коллизию до перехода на числовой суффикс.
     * Модели переопределяют: товару логично дописать артикул.
     */
    protected function slugDisambiguator(): ?string
    {
        return null;
    }

    /**
     * Префикс для вырожденных имён (пустых или чисто числовых).
     */
    protected function slugFallbackPrefix(): string
    {
        return 'item';
    }
}
